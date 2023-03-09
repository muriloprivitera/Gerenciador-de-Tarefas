<?php
    namespace controleApi;
    use Exception;
    require_once("../vendor/autoload.php");
    if (filter_var(ini_get("memory_limit"), FILTER_SANITIZE_NUMBER_INT) < 256) {
    ini_set('memory_limit', '256M');
    }

    ob_start();

    header('Cache-Control: no-store');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, HEAD, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    header('Access-Control-Expose-Headers: Authorization');

    require_once ("{$_SERVER['DOCUMENT_ROOT']}/cadastroTarefas/api/configApi/ControladorRotas.php");
    $route          = $_SERVER['PATH_INFO'];
    $method         = strtolower($_SERVER['REQUEST_METHOD']);
    $request        = array();
    $request        = ($method !== 'get') ? json_decode(file_get_contents('php://input'), true) : array();

    if (is_null($request)) $request = array();

    $request        = array_merge($request, $_GET);

    if ($method == 'post') {
        $request = array_merge($request, $_POST);
    }
    try {
        $path = dirname(__FILE__,2);
        $dotenv = \Dotenv\Dotenv::createUnsafeImmutable($path);
        $dotenv->load();
        
        $router = \ControladorRotas\ControladorRotas::getInstance($route, $request, __DIR__."/routers", getallheaders());
        $router->$method();
        $error = ob_get_contents();
        ob_end_clean();

        $router->response();

    }catch (Exception $e) {
        http_response_code($e->getCode());
        ob_end_clean();
        header('Content-Type: text/json; charset=utf-8');
        echo json_encode(
            array(
            'st_ret' => 'ERRO',
            'detalhes' => utf8_encode($e->getMessage()),
            )
        );
    }