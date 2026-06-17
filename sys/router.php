<?php
namespace Sys;

use Sys\Http\Request;
use Sys\Http\Response;

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

    public function handleRequest(?Request $request = null)
    {
        $request = $request ?? Request::capture();
        $method = $request->method();
        $path = $request->uri();

        foreach ($this->routes as $route) {
            $pattern = '#^' . preg_replace('#\{:\w+\}#', '([^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $path, $matches) && $route['method'] === $method) {
                array_shift($matches); // Remove o primeiro item que corresponde ao caminho completo
                $callback = $route['callback'];
                $response = call_user_func_array($callback, $matches);

                if ($response instanceof Response) {
                    $response->send();
                }

                return;
            }
        }

        Response::html('Página não encontrada.', 404)->send();
    }
}
