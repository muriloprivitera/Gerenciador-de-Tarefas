<?php
    namespace cadastroTarefas\controller;
    use cadastroTarefas\model\UsuariosModel;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class UsuariosController{

        private UsuariosModel $usuariosModel;

        public function __construct()
        {
            $this->usuariosModel = new UsuariosModel();
        }

        public function insereUsuario(string $nome,string $email,string $senha):string
        {
            if($this->usuariosModel->inserirNovoUsuario($nome,$email,$senha) !== true)throw new \Exception('Ocorreu um erro ao inserir um usuario');
            return 'Usuario cadastrado com sucesso';
        }

        // public function usuarioEsqueceuSenha(string $email):string|bool
        // {
        //     $model = $this->usuariosModel->usuarioEsqueceuSenha($email);
        //     if($model == false)throw new \Exception('Ocorreu um erro ao alterar a senha, verifice o endereco de e-mail');
        //     return $model;
        // }

        public function usuarioEsqueceuSenha(string $email,string $senha):bool
        {
            $model = $this->usuariosModel->usuarioEsqueceuSenha($email,$senha);
            if($model == false)throw new \Exception('Ocorreu um erro ao alterar a senha, verifice o endereco de e-mail');
            return $model;
        }

        public function trocarSenha(string $senhaAntiga,string $senhaNova, string $email):string
        {
            if($this->usuariosModel->trocarSenha($senhaAntiga,$senhaNova,$email) === false)throw new \Exception('Ocorreu um erro ao alterar a senha');

            return 'Senha alterada com Sucesso';
        }

        public function usuarioRealizaLogin(string $email,string $senha):string
        {
            $usuario = $this->usuariosModel->usuarioRealizaLogin($email);
            if(!password_verify($senha,$usuario['senha'])) throw new \Exception('Usuario ou senha invalidos');
            return \Firebase\JWT\JWT::encode(array(
                'exp'=> time()+86400,
                'iat'=> time(),
                'email' => $usuario['email'],
                'nome' => $usuario['nome'],
                'id' => $usuario['id'],
            ),'minha_key_acesso','HS256');
        }

        public function geraTokenEsqueceuSenha(string $email):string
        {
            $usuario = $this->usuariosModel->verificaEmailBd($email);
            return \Firebase\JWT\JWT::encode(array(
                'exp'=> time()+86400,
                'iat'=> time(),
                'email' => $usuario['email'],
                'nome' => $usuario['nome'],
                'id' => $usuario['id'],
            ),'minha_key_acesso','HS256');
        }

        public function validaEnviaEmail(string $email):bool
        {
            if(empty($this->usuariosModel->verificaEmailBd($email)['email'])){
                return false;
            }
            return true;
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