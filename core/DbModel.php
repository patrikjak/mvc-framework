<?php


namespace app\core;


abstract class DbModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save()
    {
        $table_name = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $table_name (".implode(',', $attributes).") 
                                    VALUES (".implode(',', $params).")");

        foreach ($attributes as $attribute) {
            $statement->bindParam(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $where_sql = implode('AND', array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $where_sql");
        foreach ($where as $key => $item) {
            $stmt->bindParam(":$key", $item);
        }
        $stmt->execute();

        return $stmt->fetchObject(static::class);
    }
}