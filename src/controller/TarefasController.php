<?php
    namespace cadastroTarefas\controller;
    use cadastroTarefas\model\TarefasModel;

    class TarefasController{

        private TarefasModel $tarefasModel;

        public function __construct()
        {
            $this->tarefasModel = new TarefasModel();
        }

        public function insereTarefa(string $nomeTarefa,string $descricaoTarefa,int $idUsuario):string
        {
            if($this->tarefasModel->insereTarefa($nomeTarefa,$descricaoTarefa,$idUsuario) !== true)throw new \Exception('Ocorreu um erro ao inserir a tarefa');
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

        public function selecionaTodasTarefas(int $quantidade, int $inicio, int $usuarioPai):array
        {
            return $this->tarefasModel->selecionaTodasTarefas($quantidade,$inicio,$usuarioPai);
        }

        public function cadastroHorasTarefa(string $horaInicio, string $horaFim, int $id):string
        {
            if($this->tarefasModel->cadastroHorasTarefa($horaInicio,$horaFim,$id) !== true)throw new \Exception('Ocorreu ao inserir as horas');

            return 'Horas inseridas com sucesso';
        }

        public function retornaDadosRelatorio():array
        {
            return $this->tarefasModel->retornaDadosRelatorio();
        }

        public function insereCategoria(string $insereCategoria, int $id):string
        {
            if($this->tarefasModel->insereCategoria($insereCategoria,$id) !== true)throw new \Exception('Ocorreu um erro ao inserir a categoria na tarefa');

            return 'Categoria inserida com sucesso';
        }
    }

?>