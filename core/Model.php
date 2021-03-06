<?php


namespace app\core;


abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $rule_name = $rule;
                if (is_array($rule_name)) {
                    $rule_name = $rule[0];
                }
                if ($rule_name === self::RULE_REQUIRED && !$value) {
                    $this->add_error_for_rule($attribute, self::RULE_REQUIRED);
                }
                if ($rule_name === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->add_error_for_rule($attribute, self::RULE_EMAIL);
                }
                if ($rule_name === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->add_error_for_rule($attribute, self::RULE_MIN, $rule);
                }
                if ($rule_name === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->add_error_for_rule($attribute, self::RULE_MAX, $rule);
                }
                if ($rule_name === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->add_error_for_rule($attribute, self::RULE_MATCH, $rule);
                }
                if ($rule_name === self::RULE_UNIQUE) {
                    $class_name = $rule['class'];
                    $unique_attr = $rule['attribute'] ?? $attribute;
                    $table_name = $class_name::table_name();
                    $stmt = DbModel::prepare("SELECT * FROM $table_name WHERE $unique_attr = :attr");
                    $stmt->bindParam(":attr", $value);
                    $stmt->execute();
                    $record = $stmt->fetchObject();
                    if ($record) {
                        $this->add_error_for_rule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function add_error_for_rule(string $attribute, string $rule, array $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? "";
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function add_error(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => "This field must be filled",
            self::RULE_EMAIL => "This field must be valid email address",
            self::RULE_MIN => "Min length of this field must be {min}",
            self::RULE_MAX => "Max length of this field must be {max}",
            self::RULE_MATCH => "This field must be same as {match}",
            self::RULE_UNIQUE => "This {field} already exists"
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute) {
        return $this->labels()[$attribute] ?? $attribute;
    }
}