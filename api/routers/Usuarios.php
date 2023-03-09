<?php
    namespace api\routers;
    use \cadastroTarefas\controller\UsuariosController;
    use ControladorRotas\ControladorRotas;
    use \cadastroTarefas\helpers\Email;

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

        private function usuarioEsqueceuSenha():mixed
        {
            try {
                $infoUso = UsuariosController::validaTokenCodificado($this->headers['authorization']);
                $mensagemEmail = '';
                $email = new Email();
                $this->usuariosController->usuarioEsqueceuSenha($infoUso->email);
                $email->enviaEmail($infoUso->email,'Sua nova senha',$this->usuariosController->usuarioEsqueceuSenha($infoUso->email))? $mensagemEmail = 'Senha alterada, enviando a senha para seu e-mail' : $mensagemEmail = $email->retornaErro();
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $mensagemEmail
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                ));
            }
        }

        private function trocarSenha():mixed
        {
            try {
                $infoUso = UsuariosController::validaTokenCodificado($this->headers['authorization']);
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $this->usuariosController->trocarSenha($this->request['senhaAntiga'],$this->request['senhaNova'],$infoUso->email)
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

    }
?>