<?php

namespace app\core;

use app\models\User;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;
    public string $user_class;
    public string $layout = 'main';
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public View $view;
    public $user;

    /**
     * Check if user is guest
     *
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Application constructor.
     * @param $rootPath
     * @param array $config
     */
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->user_class = $config['user_class'];
        $this->session = new Session();
        $this->view = new View();

        $primary_value = $this->session->get('user');
        if ($primary_value) {
            $primary_key = $this->user_class::primaryKey();
            $this->user = $this->user_class::findOne([$primary_key => $primary_value]);
        }
        else {
            $this->user = null;
        }
    }

    /**
     * Run the app, solve actual route, if route not exists, render error page
     */
    public function run()
    {
        try {
            echo $this->router->resolve();
        }
        catch (\Exception $exception) {
            $this->response->set_status_code($exception->getCode());
            echo $this->view->renderView('/errors/_error', [
                'exception' => $exception
            ]);
        }
    }

    /**
     * Save logged user to session
     *
     * @param DbModel $user
     * @return bool
     */
    public function login(DbModel $user)
    {
        $this->user = $user;
        $primary_key = $user->primaryKey();
        $primary_value = $user->{$primary_key};
        $this->session->set('user', $primary_value);
        return true;
    }

    /**
     * Logout current user and destroy session
     *
     */
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}