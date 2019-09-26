<?php

namespace Hetg\LunchGenerator\Router;

class Router
{
    private static $routes = [];

    private static $allowedMethods = ['get', 'post', 'put', 'delete'];

    public static function getRoutes(){
        return self::$routes;
    }

    public static function add(string $method, string $path, string $controller){
        if (in_array(strtolower($method), self::$allowedMethods)){
            self::$routes[$path] = [
                'controller' => $controller,
                'method' => $method
            ];
        } else {
            throw new \Exception('Wrong method');
        }

    }

    public static function resolve(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];

        if (!key_exists($path, self::$routes)){
            throw new \Exception('Not Found', 404);
        }

        if (!in_array(strtolower($method), self::$allowedMethods)){
            throw new \Exception('Method Not Allowed', 405);
        }

        [$controller, $action] = explode('::',self::$routes[$path]['controller']);

        return (new $controller())->$action();
    }

}