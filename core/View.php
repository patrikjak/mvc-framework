<?php


namespace app\core;


class View
{
    public string $title = '';

    /**
     *
     * Render view
     * @param string $view
     * @param array $params
     * @return string|string[]
     */
    public function renderView(string $view, array $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     *
     * Render content
     * @param string $viewContent
     * @return string|string[]
     */
    public function renderContent(string $viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     *
     * Get layout content
     * @return false|string
     */
    protected function layoutContent()
    {
        $layout = Application::$app->layout;
        if (Application::$app->controller) {
            $layout = Application::$app->controller->layout;
        }

        ob_start();
        include_once Application::$ROOT_DIR."/views/layout/$layout.php";
        return ob_get_clean();
    }

    /**
     *
     * Get only view
     * @param $view
     * @param $params
     * @return false|string
     */
    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

}