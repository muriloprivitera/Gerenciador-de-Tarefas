<?php
    namespace api\routers;
    use \ControladorRotas\ControladorRotas;

    use \cadastroTarefas\controller\TarefasController;

    class Tarefas extends ControladorRotas{

        private TarefasController $tarefasController;

        public function __construct($paramsUrl, $request, $headers)
        {
            parent::__construct($paramsUrl, $request, $headers);

            $this->tarefasController = new TarefasController();
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

        private function selecionaTodasTarefas():mixed
        {
            try {
                $tarefas = $this->tarefasController->selecionaTodasTarefas($this->request['quantidade'],$this->request['inicio']);
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> 'Tarefas buscadas com sucesso',
                    'tarefas'=> $tarefas
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                    'tarefas' => []
                ));
            }
        }

        private function insereTarefa():mixed
        {
            try {
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $this->tarefasController->insereTarefa($this->request['nomeTarefa'],$this->request['descricaoTarefa'])
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                ));
            }
        }

        private function alteraTarefa():mixed
        {
            try {
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $this->tarefasController->alteraTarefa($this->request['nomeTarefa'],$this->request['descricaoTarefa'],$this->request['id'])
                ));
            } catch (\Exception $e) {
                return $this->body(array(
                    'status'   => 'Erro',
                    'mensagem' => $e->getMessage(),
                ));
            }
        }

        private function excluiTarefa():mixed
        {
            try {
                return $this->body(array(
                    'status'=>'OK',
                    'mensagem'=> $this->tarefasController->excluiTarefa($this->request['id'])
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