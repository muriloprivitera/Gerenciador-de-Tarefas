<?php
    namespace cadastroTarefas\model;
    
    use \banco\DataBase;
    use cadastroTarefas\helpers\TarefasHelpers;
    use PDO;
    class TarefasModel{

        private object $conexao;//procurar saber

        public function __construct()
        {
            $this->conexao = DataBase::novaConexao();
            date_default_timezone_set('America/Sao_Paulo');
        }

        public function insereTarefa(string $nomeTarefa,string $descricaoTarefa):bool
        {
            $criadoEm = date("Y-m-d H:i:s");

            $query = "INSERT INTO tarefa(nome_tarefa,descricao_tarefa,criado_em)
                        VALUES(?,?,?)";
            return $this->conexao->prepare($query)->execute([$nomeTarefa,$descricaoTarefa,$criadoEm]);
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

        public function selecionaTodasTarefas(int $quantidade, int $inicio):array
        {
            
            $query = "SELECT * FROM tarefa LIMIT :quantidade OFFSET :inicio";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':quantidade',$quantidade,PDO::PARAM_INT);
            $stmt->bindValue(':inicio',$inicio,PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() == 0)return array();
            return $resultado;
        }

        public function cadastroHorasTarefa(string $horaInicio, string $horaFim, int $id):bool
        {
            $horaCalculada = TarefasHelpers::calculaHorasGasta($horaInicio,$horaFim);
            $query = "UPDATE tarefa SET hora_inicio =?,
                        hora_fim = ?,
                        hora_calculada = ?,
                        status_tarefa = ?
                    WHERE id = ?";

            return $this->conexao->prepare($query)->execute([$horaInicio,$horaFim,$horaCalculada,'F',$id]);
        }
    }