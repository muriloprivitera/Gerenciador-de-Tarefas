<?php
    namespace cadastroTarefas\model;
    use \banco\DataBase;

    class UsuariosModel{
        private object $conexao;

        public function __construct()
        {
            $this->conexao = DataBase::novaConexao();
            date_default_timezone_set('America/Sao_Paulo');
        }

        public function inserirNovoUsuario(string $nome,string $email,string $senha):bool
        {
            $senhaCriptografada = password_hash($senha,PASSWORD_DEFAULT);
            $criadoEm = date("Y-m-d H:i:s");
            if(count($this->usuarioRealizaLogin($email)) > 0)return false;
            $query = "INSERT INTO usuario (nome,email,senha,criado_em)
                        VALUES(?,?,?,?)";
            return $this->conexao->prepare($query)->execute([$nome,$email,$senhaCriptografada,$criadoEm]);
        }

        public function usuarioEsqueceuSenha(string $email):string|bool
        {
            $usuario = $this->verificaEmailBd($email);
            if(count($usuario) ==  0)return false;
            $novaSenha = time();
            $novaSenhaCriptografada = password_hash($novaSenha,PASSWORD_DEFAULT);
            $atualizadoEm = date("Y-m-d H:i:s");
            $query = "UPDATE usuario SET
                senha = ?,
                atualizado_em = ?
                WHERE email = ? LIMIT 1 ";
            $this->conexao->prepare($query)->execute([$novaSenhaCriptografada,$atualizadoEm,$email]);
            return $novaSenha;
        }

        public function trocarSenha(string $senhaAntiga,string $novaSenha,string $email):bool
        {
            $usuario = $this->verificaEmailBd($email);
            if(!password_verify($senhaAntiga,$usuario[0]['senha']))return false;
            if(count($usuario) ==  0)return false;
            $atualizadoEm = date("Y-m-d H:i:s");
            $query = "UPDATE usuario SET
                        senha = ?,
                        atualizado_em = ?
                        WHERE email = ? LIMIT 1";
            return $this->conexao->prepare($query)->execute([$novaSenha,$atualizadoEm,$email]);
        }

        public function usuarioRealizaLogin(string $email):array
        {
            $usuarioExistente = $this->verificaEmailBd($email);
            return $usuarioExistente;
        }

        private function verificaEmailBd(string $email):array
        {
            $query = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':email',$email);
            $stmt->execute();
            $resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $resultado;
        }
    }
?>