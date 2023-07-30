<?php
namespace Sys;


class Router
{
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($this->routes as $route) {
            $pattern = '#^' . preg_replace('#\{:\w+\}#', '(\w+)', $route['path']) . '$#';
            if (preg_match($pattern, $path, $matches) && $route['method'] === $method) {
                array_shift($matches); // Remove o primeiro item que corresponde ao caminho completo
                $callback = $route['callback'];
                call_user_func_array($callback, $matches);
                return;
            }
        }

        echo "404 Not Found";
    }
}
