<?php


namespace app\controllers;


use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Model;
use app\core\Request;
use app\core\Response;
use app\models\Login;
use app\models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response)
    {
        $login_form = new Login();
        if ($request->isPost()) {
            $login_form->loadData($request->getBody());
            if ($login_form->validate() && $login_form->login()) {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout("auth");
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    public function register(Request $request)
    {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'You were successfully registered');
                Application::$app->response->redirect('/');
                die();
            }
            return $this->render('register', [
                'model' => $user
            ]);
        }
        $this->setLayout("auth");
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {
        return $this->render('profile');
    }
}