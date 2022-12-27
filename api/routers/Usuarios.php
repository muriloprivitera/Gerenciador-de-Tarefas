<?php
    namespace api\routers;
    use \cadastroTarefas\controller\UsuariosController;
    use ControladorRotas\ControladorRotas;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Usuarios extends ControladorRotas{

        private UsuariosController $usuariosController;

        public function __construct($paramsUrl, $request, $headers)
        {
            parent::__construct($paramsUrl, $request, $headers);

            $this->usuariosController = new UsuariosController();
        }

        public function get():mixed
        {
            list($metodoEspecifico) = $this->paramsUrl;

            return $this->$metodoEspecifico();
        }

        public function post():mixed
        {
            list($metodoEspecifico) = $this->paramsUrl;

            return $this->$metodoEspecifico();
        }

        public function put():mixed
        {
            list($metodoEspecifico) = $this->paramsUrl;
            return $this->$metodoEspecifico();
        }

        public function delete():mixed
        {
            list($metodoEspecifico) = $this->paramsUrl;
            return $this->$metodoEspecifico();
        }

        private function insereUsuario():mixed
        {
            try {
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $this->usuariosController->insereUsuario($this->request['nome'],$this->request['email'],$this->request['senha']),
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                ));
            }
        }

        private function login():mixed
        {
            try {
                $token = $this->usuariosController->usuarioRealizaLogin($this->request['email'],$this->request['senha']);
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> 'Usuario encontrado',
                    'token' =>$token
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                ));
            }
        }

        public static function validaTokenCodificado(string $token):mixed
        {
            $jwt = str_replace('Bearer ','',$token);
            $tokenValido = JWT::decode($jwt,new Key('minha_key_acesso','HS256'));
            if(!is_object($tokenValido))throw new \Exception("Usuario nao autorizado", 401);
            return $tokenValido;
        }
    }
?>