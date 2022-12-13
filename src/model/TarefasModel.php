<?php
    namespace cadastroTarefas\model;
    
    use \banco\DataBase;

    class TarefasModel{

        private object $conexao;//procurar saber

        public function __construct()
        {
            $this->conexao = DataBase::novaConexao();
        }

        public function insereTarefa(string $nomeTarefa,string $descricaoTarefa):bool
        {
            $criadoEm = date("Y-m-d H:i:s");

            $query = "INSERT INTO tarefa(nome_tarefa,descricao_tarefa,criado_em)
                        VALUES(?,?,?)";
            return $this->conexao->prepare($query)->execute([$nomeTarefa,$descricaoTarefa,$criadoEm]);
        }
    }