<?php


namespace app\models;


use app\core\Application;
use app\core\DbModel;
use app\core\Model;

class Login extends Model
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Your email address',
            'password' => 'Password'
        ];
    }

    public function login()
    {
        $user = (new User)->find_one(['email' => $this->email]);
        if (!$user) {
            $this->add_error('email', 'User with this email does not exists');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->add_error('password', 'Password is incorrect');
            return false;
        }

        return Application::$app->login($user);
    }
}