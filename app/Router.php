<?php

namespace App;

class Router
{
    private $handlers;
    private $notFoundHandler;
    private const METHOD_GET = "GET";
    private const METHOD_POST = "POST";

    public function get($path, $handler)
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    private function addHandler($method, $path, $handler)
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }

    public function addNotFoundHandler($handler)
    {
        $this->notFoundHandler = $handler;
    }

    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $callback = NULL;

        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $requestPath && $method === $handler['method']) {
                $callback = $handler['handler'];
            }
        }

        if (!$callback) {
            header("HTTP/1.0 404 Not Found");
            if (!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        };

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }
}
