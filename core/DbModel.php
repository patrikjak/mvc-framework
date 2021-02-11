<?php


namespace app\core;


abstract class DbModel extends Model
{
    abstract public function table_name(): string;

    abstract public function attributes(): array;

    public function save()
    {
        $table_name = $this->table_name();
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
}