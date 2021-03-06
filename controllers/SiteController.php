<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ContactFrom;

class SiteController extends Controller
{
    /**
     * Render home page with parameters
     *
     * @return string|string[]
     */
    public function home()
    {
        $params = [
            'name' => "Pat"
        ];
        return $this->render('home', $params);
    }

    /**
     * Render contact page
     * If request is post "send email" and set flash message
     *
     * @param Request $request
     * @param Response $response
     * @return string|string[]|void
     */
    public function contact(Request $request, Response $response)
    {
        $contact = new ContactFrom();
        if ($request->isPost()) {
            $contact->loadData($request->getBody());
            if ($contact->validate() && $contact->send()) {
                Application::$app->session->setFlash('success', 'Thanks for contacting us');
                return $response->redirect('/contact');
            }
        }
        return $this->render('contact', [
            'model' => $contact
        ]);
    }
}