<?php

namespace Core;

class View {
    public static function render($view, $arguments = []) {

        extract($arguments, EXTR_SKIP);

        $file = "../App/Views/$view"; // relative to the core directory
        if (is_readable($file)) {
            require $file;
        } else {
            // echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $arguments) {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('../App/Views');
            $twig = new \Twig_Environment($loader);
            // $twig->addGlobal('session', $_SESSION); -> Saves session as a global variable for Twig
            // $twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn());
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
        }
        echo $twig->render($template, $arguments);
    }
}