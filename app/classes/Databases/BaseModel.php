<?php namespace MusicCollection\Databases;

use MusicCollection\Utils\Logger;

/**
 * Class BaseModel
 * @package MusicCollection\Databases
 * @property null|int $id
 *
 * @example To extend this class use the following format with nullable ID and empty default values
 *          Order of the parameters is extremely important as they need to match the columns in the DB
 *
 *  class MyModel extends BaseModel
 *  {
 *      protected static string $table = '<name_of_table>';
 *      protected static array $joinForeignKeys = [
 *          '<name_of foreign_key>' => [
 *              'table' => '<name_of_table>',
 *              'model' => <ModelName>::class
 *          ]
 *      ];
 *
 *      public function __construct(
 *          public ?int $id = null,
 *          public string $name = ''
 *      ) {
 *          parent::__construct();
 *      }
 *  }
 */
abstract class BaseModel
{
    use Relationships;

    protected static string $table = '';
    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $joinForeignKeys = [];
    private string $tableName;
    protected \PDO $db;
    private Logger $logger;

    /**
     * BaseModel constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        if (static::$table === '') {
            throw new \Exception("Property 'protected static \$table' must be set within implementation");
        }

        $this->tableName = static::$table;
        $this->db = Database::i();
        $this->logger = new Logger();
    }

    /**
     * Implemented to prevent a fatal error on session storage due to PDO object being available
     *
     * @see https://wiki.php.net/rfc/custom_object_serialization
     * @return array<string|int, mixed>
     */
    public function __serialize(): array
    {
        $methods = (new \ReflectionClass($this))->getMethods(\ReflectionProperty::IS_PUBLIC);
        $additionalFields = [];
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (str_starts_with($methodName, 'get') && $method->class === get_called_class()) {
                $fieldName = lcfirst(substr($methodName, 3));
                $additionalFields[$fieldName] = $this->$fieldName;
            }
        }
        return array_merge($this->getDatabaseFieldPropertiesWithValues(), $additionalFields);
    }

    /**
     * Implemented to prevent a fatal error on session storage due to PDO object being available
     *
     * @see https://wiki.php.net/rfc/custom_object_serialization
     * @param array<string, mixed> $data
     */
    public function __unserialize(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        if (str_starts_with($name, 'getBy')) {
            $requestedGetter = strtolower(substr($name, 5));
            array_unshift($arguments, $requestedGetter);
            return call_user_func([static::class, 'getBy'], ...$arguments);
        }

        throw new \Exception("Invalid function ($name) was called");
    }

    /**
     * Always assuming the table properties are the promoted public properties in the parent class
     *
     * @return array<string, mixed>
     */
    private function getDatabaseFieldPropertiesWithValues(): array
    {
        try {
            $dynamicProperties = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
            $objectVars = get_object_vars($this);
            $properties = [];
            foreach ($dynamicProperties as $dynamicProperty) {
                if ($dynamicProperty->isPromoted()) {
                    $properties[$dynamicProperty->name] = $objectVars[$dynamicProperty->name];
                }
            }

            return $properties;
        } catch (\Exception $e) {
            Logger::error(new \Exception('BaseModel getPublicProperties failed: ' . $e->getMessage()));

            return [];
        }
    }

    /**
     * Insert or update the record in the database
     *
     * @return bool
     * @noinspection SqlResolve
     */
    public function save(): bool
    {
        $fields = $this->getDatabaseFieldPropertiesWithValues();
        $keys = array_keys($fields);

        //Check based on ID if we need to INSERT or UPDATE
        if (!empty($this->id)) {
            $updateKeys = array_map(function ($key) {
                return "`$key` = :$key";
            }, $keys);
            $implodedUpdateKeys = implode(',', $updateKeys);
            $query = "UPDATE `$this->tableName`
                      SET $implodedUpdateKeys
                      WHERE `id` = :id";
        } else {
            $implodedKeys = implode('`,`', $keys);
            $implodedValues = implode(', :', $keys);
            $query = "INSERT INTO `$this->tableName` (`$implodedKeys`)
                      VALUES (:$implodedValues)";
        }

        //Create a prepared statement and bind all values individually
        $statement = $this->db->prepare($query);
        foreach ($fields as $key => $value) {
            $statement->bindValue(':' . $key, $value);
        }

        //Add the ID to the model when it wasn't set yet after saving
        if ($statement->execute()) {
            $this->id = !empty($this->id) ? $this->id : $this->db->lastInsertId();

            return true;
        }
        Logger::error(new \Exception("DB Error: {$this->db->errorInfo()[2]}"));

        return false;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     * @noinspection SqlResolve
     * @TODO Connect to delete pivots as well (in case cascade is turned off on mysql table relations)
     */
    public static function delete(int $id): bool
    {
        $db = Database::i();
        $tableName = static::$table;
        $query = "DELETE FROM `$tableName`
                  WHERE `id` = :id";

        $statement = $db->prepare($query);

        return $statement->execute([':id' => $id]);
    }

    /**
     * @return object[]
     * @throws \Exception
     */
    public static function getAll(): array
    {
        $tableName = static::$table;
        $select = "$tableName.*";
        $joinQuery = self::getJoinQuery($select);

        return self::fetchAll("SELECT $select FROM `$tableName`$joinQuery");
    }

    /**
     * @param string|\PDOStatement $query
     * @param string|null $className
     * @return object[]
     */
    protected static function fetchAll(string|\PDOStatement $query, ?string $className = null): array
    {
        try {
            $db = Database::i();
            $items = is_string($query)
                ? $db->query($query)->fetchAll(\PDO::FETCH_ASSOC)
                : $query->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($items as $key => $item) {
                $items[$key] = self::buildFromPDO($item, $className);
            }
        } catch (\Exception $e) {
            Logger::error($e);
            $items = [];
        }
        return $items;
    }

    /**
     * Being called from __callStatic to retrieve dynamic parameters
     *
     * @param string $field
     * @param string|int|float $value
     * @return BaseModel
     * @throws \ReflectionException
     * @throws \Exception
     * @noinspection SqlResolve
     */
    private static function getBy(string $field, string|int|float $value): BaseModel
    {
        $db = Database::i();
        $tableName = static::$table;
        $select = "$tableName.*";
        $joinQuery = self::getJoinQuery($select);

        $statement = $db->prepare("SELECT $select FROM `$tableName`$joinQuery WHERE `$tableName`.`$field` = :value");
        $statement->execute([':value' => $value]);

        if (($model = $statement->fetch(\PDO::FETCH_ASSOC)) === false ||
            ($model = self::buildFromPDO($model)) === false) {
            throw new \Exception("DB Error: $field '$value' is not available in the table $tableName");
        }

        return $model;
    }

    /**
     * @param array<string|int, mixed> $params
     * @param class-string<BaseModel>|null $className
     * @return bool|BaseModel
     */
    protected static function buildFromPDO(array $params, ?string $className = null): bool|BaseModel
    {
        if (empty($params)) {
            return false;
        }

        try {
            $model = $className ?? get_called_class();
            /** @var BaseModel $model */
            return (new $model(...array_merge(array_values($params))))->setRelations($params);
        } catch (\Exception $e) {
            Logger::error($e);
            return false;
        }
    }
}
