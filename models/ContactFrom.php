<?php


namespace app\models;


class ContactFrom extends \app\core\Model
{
    public string $subject = '';
    public string $email = '';
    public string $body = '';

    public function rules(): array
    {
        return [
            'subject' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED],
            'body' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'subject' => 'Enter your subject',
            'email' => 'Email address',
            'body' => 'Body of the message',
        ];
    }

    public function send(): bool
    {
        return true;
    }
}