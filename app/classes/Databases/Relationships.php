<?php namespace MusicCollection\Databases;

use MusicCollection\Utils\Logger;

/**
 * Trait Relationships
 * @package MusicCollection\Databases
 */
trait Relationships
{
    /**
     * @var array<string, array<string|BaseModel>>
     */
    protected static array $belongsTo = [];
    /**
     * @var array<string, BaseModel>
     */
    protected array $belongsToModels = [];

    /**
     * @var array<string, array<string|BaseModel[]>>
     */
    protected static array $hasMany = [];
    /**
     * @var array<string, BaseModel[]>
     */
    protected array $hasManyModels = [];

    /**
     * @var array<string, array<string, string|string[]|BaseModel[]>>
     */
    protected static array $manyToMany = [];
    /**
     * @var array<string, BaseModel[]>
     */
    protected array $manyToManyModels = [];

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
     * @return object|object[]
     * @throws \Exception
     */
    public function __get(string $name): object|array
    {
        if (array_key_exists($name, static::$manyToMany)) {
            $manyToManyItem = static::$manyToMany[$name];
            if (isset($this->manyToManyModels[$name])) {
                return $this->manyToManyModels[$name];
            }
            $this->manyToManyModels[$name] = $this->getManyToManyItems(
                $manyToManyItem['model'],
                $manyToManyItem['pivotTable'],
                $manyToManyItem['foreignKeys']
            );
            return $this->manyToManyModels[$name];
        }

        if (array_key_exists($name, static::$hasMany)) {
            $hasManyItem = static::$hasMany[$name];
            if (isset($this->hasManyModels[$name])) {
                return $this->hasManyModels[$name];
            }
            $this->hasManyModels[$name] = $this->getHasManyItems($hasManyItem['model'], $hasManyItem['foreignKey']);
            return $this->hasManyModels[$name];
        }

        if (array_key_exists($name, static::$belongsTo)) {
            $belongsToItem = static::$belongsTo[$name];
            if (isset($this->belongsToModels[$name])) {
                return $this->belongsToModels[$name];
            }
            $this->belongsToModels[$name] = $this->getBelongsToItem($belongsToItem['model'], $belongsToItem['foreignKey']);
            return $this->belongsToModels[$name];
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
        foreach (static::$belongsTo as $relationPropertyName => $properties) {
            $relationValues = [];

            //Check all the database columns and only those that start with the relation name are stored
            $table = $properties['model']::$table;
            foreach ($databaseColumns as $databaseColumn => $value) {
                if (str_starts_with($databaseColumn, $table)) {
                    $relationValues[] = $value;
                }
            }

            //Save data for later use
            $this->belongsToModels[$relationPropertyName] = new $properties['model'](...$relationValues);
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

        foreach (static::$belongsTo as $properties) {
            $fields = (new \ReflectionClass($properties['model']))->getProperties(\ReflectionProperty::IS_PUBLIC);
            $table = $properties['model']::$table;
            foreach ($fields as $field) {
                if ($field->isPromoted()) {
                    $select .= ", {$table}.$field->name AS {$table}_$field->name";
                }
            }

            $joinQuery .= " LEFT JOIN `{$table}` ON `{$table}`.`id` = `$tableName`.`{$properties['foreignKey']}`";
        }

        return $joinQuery;
    }

    /**
     * @param class-string<BaseModel> $relationModelName
     * @param string $foreignKey
     * @return object
     * @noinspection SqlResolve
     */
    private function getBelongsToItem(string $relationModelName, string $foreignKey): object
    {
        try {
            $db = Database::i();

            $statement = $db->prepare(
                "SELECT * FROM `{$relationModelName::$table}`
                        WHERE `id` = :id"
            );
            $statement->execute([':id' => $this->$foreignKey]);

            if (($model = $statement->fetch(\PDO::FETCH_ASSOC)) === false ||
                ($model = self::buildFromPDO($model, $relationModelName)) === false) {
                throw new \Exception("DB Error: getBelongsTo item failed for relation '$relationModelName' and foreignKey '$foreignKey'");
            }

            return $model;
        } catch (\Exception $e) {
            Logger::error($e);
            return new $relationModelName();
        }
    }

    /**
     * @param class-string<BaseModel> $relationModelName
     * @param string $foreignKey
     * @return object[]
     * @noinspection SqlResolve
     */
    private function getHasManyItems(string $relationModelName, string $foreignKey): array
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
