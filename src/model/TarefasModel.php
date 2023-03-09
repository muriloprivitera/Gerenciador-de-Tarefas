<?php
    namespace cadastroTarefas\model;
    
    use \banco\DataBase;
    use cadastroTarefas\helpers\TarefasHelpers;
    use PDO;
    use \Dotenv\Dotenv;
    class TarefasModel{

        private object $conexao;

        public function __construct()
        {
            $this->conexao = DataBase::novaConexao();
            date_default_timezone_set('America/Sao_Paulo');
        }

        public function insereTarefa(string $nomeTarefa,string $descricaoTarefa,int $idUsuario):bool
        {
            $criadoEm = date("Y-m-d H:i:s");

            $query = "INSERT INTO tarefa(nome_tarefa,descricao_tarefa,criado_em,usuario_pai)
                        VALUES(?,?,?,?)";
            return $this->conexao->prepare($query)->execute([$nomeTarefa,$descricaoTarefa,$criadoEm,$idUsuario]);
        }

        public function alteraTarefa(string $nomeTarefa,string $descricaoTarefa,int $id):bool
        {
            $atualizadoEm = date("Y-m-d H:i:s");

            $query = "UPDATE tarefa
                    SET nome_tarefa = ?,
                        descricao_tarefa = ?,
                        atualizado_em = ?
                    WHERE id = ?
                    ";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute([$nomeTarefa,$descricaoTarefa,$atualizadoEm,$id]);
            if($stmt->rowCount() == 0)return false;
            return true;
        }

        public function excluiTarefa(int $id):bool
        {
            $query = "DELETE FROM tarefa WHERE id = ?";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute([$id]);
            if($stmt->rowCount() == 0)return false;
            return true;
        }

        public function selecionaTodasTarefas(int $quantidade, int $inicio, int $usuarioPai):array
        {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->load();
            print_r('<pre>');
            print_r($_ENV['USER']);
            print_r('</pre>');
            exit;
            $query = "SELECT * FROM tarefa WHERE usuario_pai = :usuarioPai LIMIT :quantidade OFFSET :inicio";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':quantidade',$quantidade,PDO::PARAM_INT);
            $stmt->bindValue(':inicio',$inicio,PDO::PARAM_INT);
            $stmt->bindValue(':usuarioPai',$usuarioPai,PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 0)return array();
            return $resultado;
        }

        public function cadastroHorasTarefa(string $horaInicio, string $horaFim, int $id):bool
        {
            $horaCalculada = TarefasHelpers::calculaHorasGasta($horaInicio,$horaFim);
            $atualizadoEm = date("Y-m-d H:i:s");
            $query = "UPDATE tarefa SET hora_inicio =?,
                        hora_fim = ?,
                        hora_calculada = ?,
                        status_tarefa = ?,
                        atualizado_em = ?
                    WHERE id = ?";

            return $this->conexao->prepare($query)->execute([$horaInicio,$horaFim,$horaCalculada,'F',$atualizadoEm,$id]);
        }

        public function retornaDadosRelatorio():array
        {
            $query = "SELECT nome_tarefa,criado_em,atualizado_em,hora_calculada FROM tarefa WHERE status_tarefa = :status_tarefa";

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':status_tarefa','F',PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 0)return array();
            return $resultado;
        }

        public function insereCategoria(string $categorias, int $id):bool
        {
            $atualizadoEm = date("Y-m-d H:i:s");
            $query = "UPDATE tarefa
                        SET categorias = ?,
                            atualizado_em = ? 
                        WHERE id = ?";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute([$categorias,$atualizadoEm,$id]);
            if($stmt->rowCount() == 0)return false;
            return true;
        }
    }