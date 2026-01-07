<?php

class ErrorController
{
    //Function to show 404 error page
    public function error404()
    {
        $page_id = "error404";
        $page_title = "Página No Encontrada";

        $view = VIEWS_PATH . '/404.php';
        require_once VIEWS_PATH . "/main.php";
    }
}
