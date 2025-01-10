<?php

namespace Framework;

use App\Controllers\ErrorController;  
use Framework\Middleware\Authorise;


class Router
{
    protected $routes = [];

    /**

    */
    public function registerRoute($method, $uri, $action, $middleware = [])
    {
        // python
        // controller, method = action.split('@')
        list($controller, $controllerMethod) = explode('@', $action)

        $this -> routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'Middleware' => $middleware
            ];
    }

    public function get($uri, $controller, $middleware = [])
    {
        $this -> registerRoute('GET', $uri, $controller, $middleware);
    }

    public function post($uri, $controller, $middleware = [])
    {
        $this -> registerRoute('POST', $uri, $controller, middleware);
    }

    public function put($uri, $controller, $middleware = [])
    {
        $this -> registerRoute('PUT', $uri, $controller, middleware);
    }

    public function delete($uri, $controller, $middleware = [])
    {
        $this -> registerRoute('DELETE', $uri, $controller, middleware);
    }

    // 3:08 

    public function route($uri)
    {
        $requestMethod = S_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        // 3:11

        foreach ($this->routes as $route) {  
    
            $uriSegments = explode('/', trim($uri, '/'));  

            $routeSegments = explode('/', trim($route['uri'], '/'));  

            $match = true;

    // Constructor for Database class
    //  Next we need to go through the registered routes one by one.

    // We explode the incoming request URI into segments. This is ready for matching.

    // We do the same for the URI of the current registered route.

    // We then set a default value for match completion, so we only need to change this when no match is found.

    // The next part is where we check the segments of the request against the segments of the registered route...

            if (count($uriSegments) === count($routeSegments) 
                && strtoupper($route['method'] === $requestMethod)) {  
                $params = [];  

                $match = true;  
                $segments = count($uriSegments);

                for ($i = 0; $i < $segments; $i++) {  
                    // If the uri's do not match and there is no parm
                    if ($routeSegments[$i] !== $uriSegments[$i] 
                        // /is the start and end of regex pattern
                        // \escapes the curly brace, making it a regular character, it's looking for it.  
                        // parenthesis is the capturing group
                        // . is any character that is not a newline
                        // + is one or more proceeding elements
                        // ? lazy making match as few of the preceding characters as possible
                        // eg /products/{id}
                        && !preg_match('/\{(.+?)}/', $routeSegments[$i])) {  
                        $match = false;  
                        break;  
                    }  
            
                    if (preg_match('/\{(.+?)}/', $routeSegments[$i], $matches)) {  
                        $params[$matches[1]] = $uriSegments[$i];  
                    }  
                }
                
                if ($match) {  
                    foreach ($route['Middleware'] as $middleware) {  
                        (new Authorise())->handle($middleware);  
                    }  
                
                    $controller = 'App\\controllers\\' . $route['controller'];  
                    $controllerMethod = $route['controllerMethod'];  
                
                    // Instantiate the controller and call the method  
                    $controllerInstance = new $controller();  
                    $controllerInstance->$controllerMethod($params);  
                    return;  
                }
            }
        }
        ErrorController::notFound();
    }
}