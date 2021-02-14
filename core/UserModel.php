<?php


namespace app\core;


abstract class UserModel extends DbModel
{
    abstract public function getName(): string;

    public function tableName(): string
    {
        // TODO: Implement tableName() method.
    }

    public function attributes(): array
    {
        // TODO: Implement attributes() method.
    }

    public function primaryKey(): string
    {
        // TODO: Implement primaryKey() method.
    }

    public function rules(): array
    {
        // TODO: Implement rules() method.
    }
}