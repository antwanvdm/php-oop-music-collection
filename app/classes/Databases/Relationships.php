<?php namespace MusicCollection\Databases;

/**
 * Trait Relationships
 * @package System\Databases
 */
trait Relationships
{
    /**
     * Transform the dynamic properties from the DB to actual Objects
     *
     * @param array $databaseColumns
     * @return self|void
     * @see BaseObject::buildFromPDO()
     */
    private function setRelations(array $databaseColumns)
    {
        if (empty($databaseColumns)) {
            return;
        }

        //Loop through foreign keys, so we can check if properties have been passed dynamically
        foreach (static::$joinForeignKeys as $joinForeignKey => $properties) {
            $relationValues = [];

            //Check all the database columns and only those that start with the relation name are stored
            foreach ($databaseColumns as $databaseColumn => $value) {
                if (str_starts_with($databaseColumn, $properties['table'])) {
                    $relationValues[] = $value;
                }
            }

            //Set the properties on the object if the property exists with the dynamic parameters
            $namespaces = explode('\\', $properties['object']);
            $relationPropertyName = strtolower(end($namespaces));
            if (property_exists($this, $relationPropertyName)) {
                $this->$relationPropertyName = new $properties['object'](...$relationValues);
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

        foreach (static::$joinForeignKeys as $joinForeignKey => $properties) {
            $fields = (new \ReflectionClass($properties['object']))->getProperties(\ReflectionProperty::IS_PUBLIC);
            foreach ($fields as $field) {
                if ($field->isPromoted()) {
                    $select .= ", {$properties['table']}.{$field->name} AS {$properties['table']}_{$field->name}";
                }
            }

            $joinQuery .= " LEFT JOIN `{$properties['table']}` ON `{$properties['table']}`.`id` = `{$tableName}`.`{$joinForeignKey}`";
        }

        return $joinQuery;
    }

    /**
     * @param class-string<BaseObject> $relationClassName
     * @param string $foreignKey
     * @return array
     * @noinspection SqlResolve
     */
    protected function getOneToManyItems(string $relationClassName, string $foreignKey): array
    {
        $statement = $this->db->prepare(
            "SELECT r.* FROM `{$relationClassName::$table}` AS r
                    LEFT JOIN `{$this->tableName}` t ON t.id = r.{$foreignKey}
                    WHERE `t`.`id` = :id"
        );
        $statement->execute([':id' => $this->id]);

        return $this->fetchAll($statement, $relationClassName);
    }

    /**
     * @param class-string<BaseObject> $relationClassName
     * @param string $pivotTable
     * @param string[] $foreignKeys
     * @return array
     * @noinspection SqlResolve
     */
    protected function getManyToManyItems(string $relationClassName, string $pivotTable, array $foreignKeys): array
    {
        $statement = $this->db->prepare(
            "SELECT r.* FROM `{$relationClassName::$table}` AS r
                    LEFT JOIN `{$pivotTable}` p ON r.id = p.{$foreignKeys[0]}
                    LEFT JOIN `{$this->tableName}` t on p.{$foreignKeys[1]} = t.id
                    WHERE `t`.`id` = :id"
        );
        $statement->execute([':id' => $this->id]);

        return $this->fetchAll($statement, $relationClassName);
    }

    /**
     * @param string $pivotTable
     * @param string[] $foreignKeys
     * @return bool
     * @noinspection SqlResolve
     */
    protected function saveManyToManyItems(string $pivotTable, array $foreignKeys, array $itemIds): bool
    {
        try {
            $this->db->beginTransaction();

            //Delete all current references
            $statement = $this->db->prepare("DELETE FROM `{$pivotTable}` WHERE `{$foreignKeys[1]}` = :id");
            $statement->execute([':id' => $this->id]);

            //Add the current references
            foreach ($itemIds as $itemId) {
                $statement = $this->db->prepare(
                    "INSERT INTO `{$pivotTable}` (`{$foreignKeys[0]}`, `{$foreignKeys[1]}`)
                            VALUES (:item_id, :id)"
                );
                $statement->execute([
                    ':item_id' => $itemId,
                    ':id' => $this->id
                ]);
            }
            $this->db->commit();
            return true;
        } catch (\PDOException) {
            $this->db->rollBack();
            return false;
        }
    }
}
