<?php namespace MusicCollection\Databases;

use MusicCollection\Utils\Logger;

/**
 * Trait Relationships
 * @package MusicCollection\Databases
 */
trait Relationships
{
    /**
     * @var array<string, string[]>
     */
    protected static array $belongsTo = [];

    /**
     * @var array<string, string[]>
     */
    protected static array $hasMany = [];

    /**
     * @var array<string, array<string, string|string[]>>
     */
    protected static array $manyToMany = [];

    /**
     * @var array<string, int[]>
     */
    protected array $manyToManyIds = [];

    /**
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return int[]|bool|void
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (str_starts_with($name, 'get') && str_ends_with($name, 'Ids')) {
            $fieldName = lcfirst(substr($name, 3, -3));
            return $this->manyToManyIds[$fieldName] ?? [];
        }

        if (str_starts_with($name, 'set') && str_ends_with($name, 'Ids')) {
            $fieldName = lcfirst(substr($name, 3, -3));
            $this->manyToManyIds[$fieldName] = $arguments[0];
            return;
        }

        if (str_starts_with($name, 'save')) {
            $fieldName = lcfirst(substr($name, 4));
            if (array_key_exists($fieldName, static::$manyToMany)) {
                $ids = $this->manyToManyIds[$fieldName] ?? [];
                $manyToManyItem = static::$manyToMany[$fieldName];
                return $this->saveManyToManyItems($manyToManyItem['pivotTable'], $manyToManyItem['foreignKeys'], $ids);
            }
        }

        throw new \Exception("Invalid function ($name) was called");
    }

    /**
     * @param string $name
     * @return object[]
     * @throws \Exception
     */
    public function __get(string $name): array
    {
        if (array_key_exists($name, static::$manyToMany)) {
            $manyToManyItem = static::$manyToMany[$name];
            return $this->getManyToManyItems($manyToManyItem['model'], $manyToManyItem['pivotTable'], $manyToManyItem['foreignKeys']);
        }

        if (array_key_exists($name, static::$hasMany)) {
            $hasManyItems = static::$hasMany[$name];
            return $this->getOneToManyItems($hasManyItems['model'], $hasManyItems['foreignKey']);
        }

        throw new \Exception("There is no relation defined for $name doesn't exist");
    }

    /**
     * Transform the dynamic properties from the DB to actual Models
     *
     * @param array<string, string|int|float> $databaseColumns
     * @return BaseModel
     * @see BaseModel::buildFromPDO()
     */
    private function setRelations(array $databaseColumns): object
    {
        if (empty($databaseColumns)) {
            Logger::info('Variable $databaseColumns was empty while calling BaseModel->setRelations');
            return $this;
        }

        //Loop through foreign keys, so we can check if properties have been passed dynamically
        foreach (static::$belongsTo as $properties) {
            $relationValues = [];

            //Check all the database columns and only those that start with the relation name are stored
            foreach ($databaseColumns as $databaseColumn => $value) {
                if (str_starts_with($databaseColumn, $properties['table'])) {
                    $relationValues[] = $value;
                }
            }

            //Set the properties on the object if the property exists with the dynamic parameters
            $namespaces = explode('\\', $properties['model']);
            $relationPropertyName = lcfirst(end($namespaces));
            if (property_exists($this, $relationPropertyName)) {
                $this->$relationPropertyName = new $properties['model'](...$relationValues);
            }
        }

        return $this;
    }

    /**
     * @param string $select
     * @return string
     * @throws \ReflectionException
     */
    private static function getJoinQuery(string &$select): string
    {
        $tableName = static::$table;
        $joinQuery = '';

        foreach (static::$belongsTo as $joinForeignKey => $properties) {
            $fields = (new \ReflectionClass($properties['model']))->getProperties(\ReflectionProperty::IS_PUBLIC);
            foreach ($fields as $field) {
                if ($field->isPromoted()) {
                    $select .= ", {$properties['table']}.$field->name AS {$properties['table']}_$field->name";
                }
            }

            $joinQuery .= " LEFT JOIN `{$properties['table']}` ON `{$properties['table']}`.`id` = `$tableName`.`$joinForeignKey`";
        }

        return $joinQuery;
    }

    /**
     * @param class-string<BaseModel> $relationModelName
     * @param string $foreignKey
     * @return object[]
     * @noinspection SqlResolve
     */
    private function getOneToManyItems(string $relationModelName, string $foreignKey): array
    {
        try {
            $db = Database::i();
        } catch (\Exception $e) {
            Logger::error($e);
            return [];
        }

        $statement = $db->prepare(
            "SELECT r.* FROM `{$relationModelName::$table}` AS r
                    LEFT JOIN `$this->tableName` t ON `t`.`id` = `r`.`$foreignKey`
                    WHERE `t`.`id` = :id"
        );
        $statement->execute([':id' => $this->id]);

        return $this->fetchAll($statement, $relationModelName);
    }

    /**
     * @param class-string<BaseModel> $relationModelName
     * @param string $pivotTable
     * @param string[] $foreignKeys
     * @return object[]
     * @noinspection SqlResolve
     */
    private function getManyToManyItems(string $relationModelName, string $pivotTable, array $foreignKeys): array
    {
        try {
            $db = Database::i();
        } catch (\Exception $e) {
            Logger::error($e);
            return [];
        }

        $statement = $db->prepare(
            "SELECT r.* FROM `{$relationModelName::$table}` AS r
                    LEFT JOIN `$pivotTable` p ON r.id = p.$foreignKeys[0]
                    LEFT JOIN `$this->tableName` t on p.$foreignKeys[1] = t.id
                    WHERE `t`.`id` = :id"
        );
        $statement->execute([':id' => $this->id]);

        return $this->fetchAll($statement, $relationModelName);
    }

    /**
     * @param string $pivotTable
     * @param string[] $foreignKeys
     * @param int[] $itemIds
     * @return bool
     * @noinspection SqlResolve
     */
    private function saveManyToManyItems(string $pivotTable, array $foreignKeys, array $itemIds): bool
    {
        try {
            $db = Database::i();
        } catch (\Exception $e) {
            Logger::error($e);
            return false;
        }

        try {
            $db->beginTransaction();

            //Delete all current references
            $statement = $db->prepare("DELETE FROM `$pivotTable` WHERE `$foreignKeys[1]` = :id");
            $statement->execute([':id' => $this->id]);

            //Add the current references
            foreach ($itemIds as $itemId) {
                $statement = $db->prepare(
                    "INSERT INTO `$pivotTable` (`$foreignKeys[0]`, `$foreignKeys[1]`)
                            VALUES (:item_id, :id)"
                );
                $statement->execute([
                    ':item_id' => $itemId,
                    ':id' => $this->id
                ]);
            }
            $db->commit();
            return true;
        } catch (\PDOException $e) {
            Logger::error($e);
            $db->rollBack();
            return false;
        }
    }
}
