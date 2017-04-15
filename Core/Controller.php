<?php

namespace Core;

abstract class Controller {

    // parameters from the matched route
    protected $route_parameters = [];

    public function __construct($route_parameters) {
        $this->route_parameters = $route_parameters;
    }

    public function __call($name, $arguments) {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $arguments);
                $this->after();
            }
        } else {
            // echo "Method $method not found in controller " . get_class($this);
            throw new \Exception("Method $method not found in controller " .
                get_class($this));
        }
    }

    protected function before() {

    }

    protected function after() {

    }

    public function redirect($url) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }
}