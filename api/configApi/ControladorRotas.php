<?php
namespace ControladorRotas;
include('RespostaRotas.php');
use Exception;
use \RespostaRotas;

abstract class ControladorRotas{

    protected array $paramsUrl;
    protected array $request;
    protected array $headers;
    protected static object $response;

    public function __construct(array $paramsUrl,array $request,array $headers)
    {
        $this->paramsUrl = $paramsUrl;
        $this->request = $request;
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        $accept        = $this->headers['accept'];
        if (empty($accept))$accept = '*/*';
        self::$response = new RespostaRotas\RespostaRotas($accept);
    }

    /**
     * Aplica codigo de retorno do mйtodo HTTP
     */
    protected function statusHttp(int $code):void
    {
        self::$response->statusHttp($code);
    }
    /**
     * Adiciona um novo header de resposta para o cliente HTTP
     */
    protected function header($title, $content):void
    {
        self::$response->headers($title, $content);
    }
    /**
     * Adicona o retorno da operaзгo na memуria para o cliente
     */
    protected function body(array|string $body): void
    {
        self::$response->body($body);
    }

    /**
     * Executa o verbo HTTP chamado
     */
    public function get():mixed
    {
        $this->statusHttp(405);
        $this->body(array(
            'message' => 'Method Not implemented'
        ));
    }
    /**
     * Executa o verbo HTTP chamado
     */
    public function post():mixed
    {
        $this->statusHttp(405);
        $this->body(array(
            'message' => 'Method Not implemented'
        ));
    }
    /**
     * Executa o verbo HTTP chamado
     */
    public function put():mixed
    {
        $this->statusHttp(405);
        $this->body(array(
            'message' => 'Method Not implemented'
        ));
    }
    /**
     * Executa o verbo HTTP chamado
     */
    public function delete():mixed
    {
        $this->statusHttp(405);
        $this->body(array(
            'message' => 'Method Not implemented'
        ));
    }
    /**
     * Mйtodo implementado por causa do axios em desenvolvimento que valida o acesso por meio do options
     */
    public function options():mixed
    {
        $this->statusHttp(200);
    }

    /**
     * Prepara e envia a resposta para o recurso solicitado
     */
    public function response():void
    {
        echo self::$response->response();
    }

    /**
     * Retorna Class para rota chamada pela API
     */
    public static function getInstance(string $route, array $request, string $diretorio, array $headers) : object
    {
        if (empty($diretorio)) {
            throw new Exception('Diretуrio nгo definido');
        }
        if (preg_match('/\/$/', $diretorio)) {
            $diretorio = substr($diretorio, 0, -1);
        }
        list($rota, $params) = self::registerRoute($route);
        if (!file_exists($diretorio . "/{$rota}.php")) {
            throw new Exception("Rota nгo encontrada: " . $rota, 404);
        }
        require_once($diretorio . "/{$rota}.php");
        
        $rotaCompleta = '\api\routers\\'. $rota;
        if (!class_exists('\api\routers\\'. $rota)) {
            throw new Exception("Classe nгo encontrada: {$rota}", 404);
        }
        return new $rotaCompleta($params, $request, $headers);
    }

    /**
     * Mйtodo responsбvel por tratar chamadas de rotas com parвmetros
     */
    protected static function registerRoute(string &$route): array
    {
        $params = explode('/', substr($route, 1));
        list($rota) = $params;
        if (empty($rota)) {
            throw new Exception("Rota invбlida: {$route}");
        }
        $rota = ucfirst(strtolower($rota));
        if (strpos($rota, '-') !== false) {
            $concact = explode('-', $rota);
            $concact = array_map('ucfirst', $concact);
            $rota    = implode('', $concact);
        }
        array_shift($params); // removendo a rota do array
        return array($rota, $params);
    }
}
?>