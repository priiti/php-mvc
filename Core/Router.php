<?php

namespace Core;

class Router {

    // routing table where we have controllers and actions
    protected $routes = [];

    protected $parameters = [];

    // lisame routide tabelisse uue routingu
    public function addNewRoute($route, $parameters = []) {
        // Convert the route to a regular expression
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $parameters;

    }

    // v2ljastame routing tabelist route ehk controlleri ja action
    public function getRouteTable() {
        return $this->routes;
    }

    // kontrollime, kas route on routingu tabelis olemas. kui on paneme parameetriks
    public function match($url) {
        /*
        foreach ($this->routes as $route => $parameters) {
            if ($url == $route) {
                $this->parameters = $parameters;
                return true;
            }
        }
        return false;
        */

        // matchime fixed URL formaadi controller ja action

        // $reg_expression = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";

        foreach ($this->routes as $route => $parameters) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $parameters[$key] = $match;
                    }
                }
                $this->parameters = $parameters;
                return true;
            }
        }
        return false;
    }

    public function dispatch($url) {

        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->parameters['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            //$controller = "App\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->parameters);

                $action = $this->parameters['action'];
                $action = $this->convertToCamelCase($action);

                if (is_callable([$controller_object, $action])) {
                    $controller_object->$action();
                } else {
                    // echo 'Method $action (in controller $controller) not found.';
                    throw new \Exception("Method $action (in controller $controller) not found.");
                }
            } else {
                // echo 'Controller class $controller not found.';
                throw new \Exception("Controller class $controller not found.");
            }
        } else {
            // echo 'No route matched.';
            throw new \Exception("No route matched.", 404);
        }
    }

    public function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', '', $string)));
    }

    public function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    public function getParameters() {
        return $this->parameters;
    }

    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    protected function getNamespace() {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->parameters)) {
            $namespace .= $this->parameters['namespace'] . '\\';
        }

        return $namespace;
    }
}