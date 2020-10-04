<?php
/**
 * Simple route class
 * based on steampixel's blog route
 *
 * @author inlee <einable@gmail.com>
 * @see https://steampixel.de/en/simple-and-elegant-url-routing-with-php/
 * @see https://stackoverflow.com/questions/11722711/url-routing-regex-php
 */

namespace Foundation;

class Route
{
    /**
     * Route List
     *
     * @var array
     */
    private static $routes = array();

    /**
     * 404 Not Found Callback
     *
     * @var function
     */
    private static $notFound = null;

    /**
     * 405 Method Not Allowed Callback
     *
     * @var function
     */
    private static $methodNotAllowed = null;

    /**
     * 404 Not Found를 처리할 Handler를 등록한다.
     *
     * @param function $callback    Callback function
     */
    public static function notFound($callback)
    {
        self::$notFound = $callback;
    }

    /**
     * 405 Method Not Allowed를 처리할 Handler를 등록한다.
     *
     * @param function $callback    Callback function
     */
    public static function methodNotAllowed($allback)
    {
        self::$methodNotAllowed = $callback;
    }

    /**
     * Route 정보를 추가한다.
     *
     * @param string $url           URL (with regex)
     * @param function $callback    Callback function
     * @param string $method        HTTP Method. default is get.
     */
    public static function add($url, $callback, $method = 'get')
    {
        array_push(self::$routes, array(
            'url' => $url,
            'callback' => $callback,
            'method' => $method
        ));
    }

    /**
     * Route를 실행한다.
     *
     * @param string $basePath      기본으로 추가될 URL
     */
    public static function run($basePath = '/')
    {
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // method가 post인 경우, magic method 존재여부 확인
        // put, delete 등 확인
        // _method 항목이 존재하면 해당 method 사용
        if (strtolower($method) == 'post') {
            $method = isset($_REQUEST['_method'])
                ? $_REQUEST['_method']
                : 'post';
        }

        $pathMatchFound = false;
        $routeMatchFound = false;

        foreach (self::$routes as $route) {
            $pattern = $route['url'];
            if ($basePath != '' && $basePath != '/') {
                $pattern = "{$basePath}{$pattern}";
            }

            $pattern =
                "@^" .
                preg_replace(
                    '/\\\:[a-zA-Z0-9\_\-]+/',
                    '([a-zA-Z0-9\-\_]+)',
                    preg_quote($pattern)
                ) .
                "$@D";

            // Check path match
            if (preg_match($pattern, $path, $matches)) {
                $pathMatchFound = true;

                // Check method match
                if (strtolower($method) == strtolower($route['method'])) {
                    // Always remove first element.
                    // This contains the whole string
                    array_shift($matches);

                    // $_REQUEST 추가
                    array_unshift($matches, $_REQUEST);

                    // callback 함수 호출
                    echo self::callCallback($route['callback'], $matches);

                    $routeMatchFound = true;

                    break;
                }
            }
        }

        // Route method를 찾지 못한 경우
        if (!$routeMatchFound) {
            // But a matching path exists
            if ($pathMatchFound) {
                echo self::handleMethodNotAllowed($_REQUEST);
            } else {
                echo self::handleNotFound($_REQUEST);
            }
        }
    }

    /**
     * Callback 함수를 호출한다.
     *
     * @param string|array|function $callback   Callback 함수
     * @param array $matches                    Parameter
     */
    private static function callCallback($callback, $matches)
    {
        if (is_array($callback)) {
            // callback 정보가 배열인 경우. ex) array(classname, methodname)
            $inst = new $callback[0]();
            return call_user_func_array(array($inst, $callback[1]), $matches);
        } else {
            // callback 정보가 문자열인 경우
            if (strpos($callback, '@') !== false) {
                // 클래스 이름과 메소드가 정의된 경우. ex) class@method
                $classMethod = explode('@', $callback);

                $className = $classMethod[0];
                $methodName = $classMethod[1];

                $inst = new $className();

                return call_user_func_array(
                    array($inst, $methodName),
                    $matches
                );
            } else {
                // callback function인 경우
                return call_user_func_array($callback, $matches);
            }
        }
    }

    /**
     * URL path가 없을 경우 예외처리 한다.
     *
     * @param array $request    $_REQUEST
     */
    private static function handleNotFound($request)
    {
        header("HTTP/1.0 404 Not Found");

        if (self::$notFound) {
            return self::callCallback(self::$notFound, array($_REQUEST));
        }
    }

    /**
     * URL Path는 존재하나 등록된 Method(GET, POST 등)가
     * 일치하지 않을 경우 예외처리 한다.
     *
     * @param array $request    $_REQUEST
     */
    private static function handleMethodNotAllowed($request)
    {
        header("HTTP/1.0 405 Method Not Allowed");

        if (self::$methodNotAllowed) {
            return self::callCallback(self::$methodNotAllowed, array(
                $_REQUEST
            ));
        }
    }
}
