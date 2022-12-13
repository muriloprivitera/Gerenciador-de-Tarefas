<?php

    namespace banco;

    use PDO;

    class DataBase extends PDO{
        private static $host  = "localhost";
        private static $user  = "root";
        private static $senha = "";
        private static $banco = "tarefas";

        public static function novaConexao():object
        {
            try {
                $conexao = new PDO("mysql:host=".self::$host.";dbname=".self::$banco."",self::$user,self::$senha);
                return $conexao;
            } catch (\PDOException $e) {
                die("Erro: ".$e->getMessage());
            }
        }
    }
?>