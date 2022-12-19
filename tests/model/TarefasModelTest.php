<?php
    namespace cadastroTarefasTest\TarefasModelTest;

    use PHPUnit\Framework\TestCase;
    use \cadastroTarefas\model\TarefasModel;

    class TarefasModelTest extends TestCase{

        protected TarefasModel $tarefasModel;

        public function setUp():void
        {   
            $this->tarefasModel = new TarefasModel();
        }

        public function testInsereTarefa():void
        {
            $this->assertTrue($this->tarefasModel->insereTarefa('testTarefa2','testeTarefa'));
        }

        public function testAlteraTarefa():void
        {
            $this->assertTrue($this->tarefasModel->alteraTarefa('novo Nome murilo','isso e um teste de update',11));
        }

        public function testExcluiTarefa():void
        {
            $this->assertTrue($this->tarefasModel->excluiTarefa(4));
        }

        public function testSelecionaTodasTarefas():void
        {
            $this->assertGreaterThan(0,count($this->tarefasModel->selecionaTodasTarefas(300,1)));
        }

        public function testCadastroHorasTarefa():void
        {
            $this->assertTrue($this->tarefasModel->cadastroHorasTarefa('10:50:22','13:30:25',1));
        }

        public function testRetornaDadosRelatorio():void
        {
            $this->assertGreaterThan(0,count($this->tarefasModel->retornaDadosRelatorio()));
        }

        public function testInsereCategorias():void
        {
            $this->assertTrue($this->tarefasModel->insereCategoria('pessoal,david',11));
        }
    }

?>