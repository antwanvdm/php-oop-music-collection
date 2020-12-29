<?php /** @noinspection SqlResolve */
namespace System\Databases;

/**
 * Trait Relationships
 * @package System\Databases
 * @TODO Make sure we implement something like "with" to support eager loading
 */
trait Relationships
{
    /**
     * Make sure we can call relationships without using the function name
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        }

        throw new \Exception("Relationship for {$name} does not exist on " . get_called_class());
    }

    /**
     * Make sure we can set relationships without using the function name
     *
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     */
    public function __set(string $name, $value)
    {
        if (method_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new \Exception("Relationship for {$name} does not exist on " . get_called_class());
        }
    }

    /**
     * @param string $className
     * @param string $foreignKey
     * @return object
     */
    protected function belongsTo(string $className, string $foreignKey): object
    {
        $fullClassName = '\\System\\Databases\\Objects\\' . $className;
        if ($this->$foreignKey !== null) {
            return $fullClassName::getById($this->$foreignKey);
        }

        return new $fullClassName();
    }

    /**
     * @param string $className
     * @param string $foreignKey
     * @return array
     */
    protected function hasMany(string $className, string $foreignKey): array
    {
        /** @var BaseObject $fullClassName */
        $fullClassName = '\\System\\Databases\\Objects\\' . $className;

        return $fullClassName::getAllByForeignKey($foreignKey, $this->id);
    }

    /**
     * @param string $relationName
     * @param string $className
     * @param array $foreignKeys
     * @param string $pivotTable
     * @return array
     */
    protected function belongsToMany(string $relationName, string $className, array $foreignKeys, string $pivotTable): array
    {
        /** @var BaseObject $currentClass */
        $relationClassName = '\\System\\Databases\\Objects\\' . $className;
        $currentClass = get_called_class();

        return $currentClass::getAllThroughPivot($foreignKeys, $this->id, $pivotTable, $relationClassName);
    }

    /**
     * @param string $foreignKey
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public static function getAllByForeignKey(string $foreignKey, int $id): array
    {
        $db = Database::getInstance();
        $tableName = static::$table;

        $statement = $db->prepare("SELECT * FROM `{$tableName}` WHERE `{$foreignKey}` = :id");
        $statement->execute([':id' => $id]);

        return $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class());
    }

    /**
     * Get the related items via a pivot table
     *
     * @param array $foreignKeys
     * @param int $id
     * @param string $pivotTable
     * @return array
     * @throws \Exception
     */
    public static function getAllThroughPivot(array $foreignKeys, int $id, string $pivotTable, string $relationClassName): array
    {
        $db = Database::getInstance();
        $tableName = static::$table;
        $relationTableName = $relationClassName::$table;

        $statement = $db->prepare(
            "SELECT r.* FROM `{$tableName}` AS t
                        INNER JOIN `{$pivotTable}` AS p ON p.{$foreignKeys[0]} = t.id
                        INNER JOIN `{$relationTableName}` AS r ON r.id = p.{$foreignKeys[1]}
                        WHERE t.id = :id");
        $statement->execute([':id' => $id]);

        return $statement->fetchAll(\PDO::FETCH_CLASS, $relationClassName);
    }

    /**
     * Save the relationship items for many to many in the DB
     */
    private function savePivots(): void
    {
        $objectVars = get_object_vars($this);
        foreach ($objectVars as $key => $value) {
            if (method_exists($this, $key)) {
                $this->savePivot($objectVars[$key]);
            }
        }
    }

    /**
     * @param array $values
     * @TODO Refactor please
     */
    private function savePivot(array $values): void
    {
        $currentTableSingular = substr($this->tableName, 0, -1);
        foreach ($values as $key => $object) {
            //Very dependant on respecting naming conventions
            $relationTableSingular = substr($object->tableName, 0, -1);
            $pivotTable = $currentTableSingular . '_' . $relationTableSingular;
            //Simple delete all current connections before adding new ones (and possibly the same)
            if ($key === 0) {
                $statement = $this->db->prepare("DELETE FROM `{$pivotTable}` WHERE `{$currentTableSingular}_id` = :id");
                $statement->execute([':id' => $this->id]);
            }

            //Insert the new relations in the pivot table
            $query = "INSERT INTO `{$pivotTable}` (`{$currentTableSingular}_id`, `{$relationTableSingular}_id`)
                      VALUES (:tableId, :relationId)";

            $statement = $this->db->prepare($query);
            $statement->execute([':tableId' => $this->id, ':relationId' => $object->id]);
        }
    }
}
