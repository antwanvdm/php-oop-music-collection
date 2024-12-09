<?php namespace MusicCollection\Databases;

use MusicCollection\Utils\Logger;
use MusicCollection\Utils\Reflection;

/**
 * Trait Relationships
 * @package MusicCollection\Databases
 */
trait Relationships
{
    /**
     * @var array<string, array{foreignKey: string, model: class-string<BaseModel>}>
     */
    protected static array $belongsTo = [];
    /**
     * @var array<string, BaseModel>
     */
    protected array $belongsToModels = [];

    /**
     * @var array<string, array{foreignKey: string, model: class-string<BaseModel>}>
     */
    protected static array $hasMany = [];
    /**
     * @var array<string, BaseModel[]>
     */
    protected array $hasManyModels = [];

    /**
     * @var array<string, array{pivotTable: string, foreignKeys: string[], model: class-string<BaseModel>}>
     */
    protected static array $belongsToMany = [];
    /**
     * @var array<string, BaseModel[]>
     */
    protected array $belongsToManyModels = [];

    /**
     * @var array<string, int[]>
     */
    protected array $belongsToManyIds = [];

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
            return $this->belongsToManyIds[$fieldName] ?? [];
        }

        if (str_starts_with($name, 'set') && str_ends_with($name, 'Ids')) {
            $fieldName = lcfirst(substr($name, 3, -3));
            $this->belongsToManyIds[$fieldName] = $arguments[0];
            return;
        }

        if (str_starts_with($name, 'save')) {
            $fieldName = lcfirst(substr($name, 4));
            if (array_key_exists($fieldName, static::$belongsToMany)) {
                $ids = $this->belongsToManyIds[$fieldName] ?? [];
                $belongsToManyItem = static::$belongsToMany[$fieldName];
                return $this->saveBelongsToManyItems($belongsToManyItem['pivotTable'], $belongsToManyItem['foreignKeys'], $ids);
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
        if (array_key_exists($name, static::$belongsTo)) {
            $belongsToItem = static::$belongsTo[$name];
            if (isset($this->belongsToModels[$name])) {
                return $this->belongsToModels[$name];
            }
            $this->belongsToModels[$name] = $this->getBelongsToItem($belongsToItem['model'], $belongsToItem['foreignKey']);
            return $this->belongsToModels[$name];
        }

        if (array_key_exists($name, static::$belongsToMany)) {
            $belongsToManyItem = static::$belongsToMany[$name];
            if (isset($this->belongsToManyModels[$name])) {
                return $this->belongsToManyModels[$name];
            }
            $this->belongsToManyModels[$name] = $this->getBelongsToManyItems(
                $belongsToManyItem['model'],
                $belongsToManyItem['pivotTable'],
                $belongsToManyItem['foreignKeys']
            );
            $this->belongsToManyIds[$name] = array_map(fn (BaseModel $model) => $model->id, $this->belongsToManyModels[$name]);
            return $this->belongsToManyModels[$name];
        }

        if (array_key_exists($name, static::$hasMany)) {
            $hasManyItem = static::$hasMany[$name];
            if (isset($this->hasManyModels[$name])) {
                return $this->hasManyModels[$name];
            }
            $this->hasManyModels[$name] = $this->getHasManyItems($hasManyItem['model'], $hasManyItem['foreignKey']);
            return $this->hasManyModels[$name];
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
            if (!empty($relationValues)) {
                $this->belongsToModels[$relationPropertyName] = new $properties['model'](...$relationValues);
            }
        }

        foreach (static::$belongsToMany as $relationPropertyName => $properties) {
            $models = $this->getModelsForManyRelationTypes($databaseColumns, $properties['model']);
            if (!empty($models)) {
                $this->belongsToManyModels[$relationPropertyName] = $models;
                $this->belongsToManyIds[$relationPropertyName] = array_map(fn (BaseModel $model) => $model->id, $models);
            }
        }

        foreach (static::$hasMany as $relationPropertyName => $properties) {
            $models = $this->getModelsForManyRelationTypes($databaseColumns, $properties['model']);
            if (!empty($models)) {
                $this->hasManyModels[$relationPropertyName] = $this->getModelsForManyRelationTypes($databaseColumns, $properties['model']);
            }
        }

        return $this;
    }

    /**
     * When initially setting the relations, hasMany and belongsToMany have very identical behaviour
     * This method combines the logic and explodes the initial result set
     *
     * @param array<string, string|int|float|null> $databaseColumns
     * @param class-string<BaseModel> $modelName
     * @return BaseModel[]
     * @see BaseModel::setRelations()
     * @see BaseModel::getSelectGroupQueryForManyModels()
     */
    private function getModelsForManyRelationTypes(array $databaseColumns, string $modelName): array
    {
        $models = [];

        //Check all the database columns and only those that match the relation name are stored
        $table = $modelName::$table;
        foreach ($databaseColumns as $databaseColumn => $value) {
            if ($databaseColumn === $table && $value !== null) {
                $relationItems = explode('||', $value);
                foreach ($relationItems as $relationItem) {
                    $relationValues = explode(';;', $relationItem);
                    $models[] = new $modelName(...$relationValues);
                }
            }
        }

        return $models;
    }

    /**
     * @param string $select
     * @param string[] $with
     * @return string
     * @throws \ReflectionException
     */
    private static function getJoinQuery(string &$select, array $with = []): string
    {
        $tableName = static::$table;
        $joinQuery = '';

        foreach (static::$belongsTo as $relationName => $properties) {
            //Only set when actually available
            if (!in_array($relationName, $with)) {
                continue;
            }

            $fields = Reflection::getPromotedPublicProperties($properties['model']);
            $table = $properties['model']::$table;
            foreach ($fields as $field) {
                $select .= ", `$table`.`$field->name` AS {$table}_$field->name";
            }

            $joinQuery .= " LEFT JOIN `$table` ON `$table`.`id` = `$tableName`.`{$properties['foreignKey']}`";
        }

        foreach (static::$belongsToMany as $relationName => $properties) {
            //Only set when actually available
            if (!in_array($relationName, $with)) {
                continue;
            }
            $select .= self::getSelectGroupQueryForManyModels($properties['model']);
            $table = $properties['model']::$table;

            $joinQuery .= " LEFT JOIN `{$properties['pivotTable']}` ON `{$properties['pivotTable']}`.`{$properties['foreignKeys'][1]}` = `$tableName`.`id`";
            $joinQuery .= " LEFT JOIN `$table` ON `$table`.`id` = `{$properties['pivotTable']}`.`{$properties['foreignKeys'][0]}`";
        }

        foreach (static::$hasMany as $relationName => $properties) {
            //Only set when actually available
            if (!in_array($relationName, $with)) {
                continue;
            }
            $select .= self::getSelectGroupQueryForManyModels($properties['model']);
            $table = $properties['model']::$table;

            $joinQuery .= " LEFT JOIN `$table` ON `$table`.`{$properties['foreignKey']}` = `$tableName`.`id`";
        }

        return $joinQuery;
    }

    /**
     * When creating the JOIN queries, hasMany and belongsToMany have very identical behaviour
     * Only the final JOIN queries differ, so the other logic is executed in this method
     *
     * @param class-string<BaseModel> $modelName
     * @return string
     * @throws \ReflectionException
     * @see BaseModel::getJoinQuery()
     * @see BaseModel::getModelsForManyRelationTypes()
     */
    private static function getSelectGroupQueryForManyModels(string $modelName): string
    {
        $fields = Reflection::getPromotedPublicProperties($modelName);
        $table = $modelName::$table;
        $columns = [];
        foreach ($fields as $field) {
            $columns[] = "$table.$field->name";
        }
        $joinedColumns = implode(",';;',", $columns);
        return ", GROUP_CONCAT(DISTINCT CONCAT($joinedColumns) SEPARATOR '||') AS $table";
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
                        WHERE id = :id"
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
                    LEFT JOIN `$this->tableName` t ON t.id = r.`$foreignKey`
                    WHERE t.id = :id"
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
    private function getBelongsToManyItems(string $relationModelName, string $pivotTable, array $foreignKeys): array
    {
        try {
            $db = Database::i();
        } catch (\Exception $e) {
            Logger::error($e);
            return [];
        }

        $statement = $db->prepare(
            "SELECT r.* FROM `{$relationModelName::$table}` AS r
                    LEFT JOIN `$pivotTable` p ON r.id = p.`$foreignKeys[0]`
                    LEFT JOIN `$this->tableName` t on p.`$foreignKeys[1]` = t.id
                    WHERE t.id = :id"
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
    private function saveBelongsToManyItems(string $pivotTable, array $foreignKeys, array $itemIds): bool
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
