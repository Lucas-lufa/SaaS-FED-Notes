use App\Controllers\ErrorController;  
use Framework\Middleware\Authorise;

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
}