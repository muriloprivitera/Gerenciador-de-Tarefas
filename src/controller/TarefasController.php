<?php
    namespace cadastroTarefas\controller;
    use cadastroTarefas\model\TarefasModel;

    class TarefasController{

        private TarefasModel $tarefasModel;

        public function __construct()
        {
            $this->tarefasModel = new TarefasModel();
        }

        public function insereTarefa(string $nomeTarefa,string $descricaoTarefa):string
        {
            if($this->tarefasModel->insereTarefa($nomeTarefa,$descricaoTarefa) !== true)throw new \Exception('Ocorreu um erro ao inserir a tarefa');
            return 'Tarefa inserida com sucesso';
        }

        public function alteraTarefa(string $nomeTarefa,string $descricaoTarefa,int $id):string
        {
            if($this->tarefasModel->alteraTarefa($nomeTarefa,$descricaoTarefa,$id) !== true)throw new \Exception('Ocorreu um erro ao alterar a tarefa');
            return 'Tarefa alterada com sucesso';
        }

        public function excluiTarefa(int $id):string
        {
            if($this->tarefasModel->excluiTarefa($id) !== true)throw new \Exception('Ocorreu um erro ao excluir a tarefa');
            return 'Tarefa excluida com sucesso';
        }

        public function selecionaTodasTarefas(int $quantidade, int $inicio):array
        {
            return $this->tarefasModel->selecionaTodasTarefas($quantidade,$inicio);
        }
    }

?>