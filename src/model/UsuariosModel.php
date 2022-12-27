<?php
    namespace cadastroTarefas\model;
    use \banco\DataBase;
    use \PDO;

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

        public function usuarioRealizaLogin(string $email):array
        {
            $usuarioExistente = $this->verificaEmailBd($email);
            return $usuarioExistente;
        }

        private function verificaEmailBd(string $email):array
        {
            $query = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':email',$email);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        }
    }
?>