<?php
    namespace cadastroTarefasTest\TarefasControllerTest;

    use PHPUnit\Framework\TestCase;
    use \cadastroTarefas\controller\TarefasController;

    class TarefasControllerTest extends TestCase{

        protected TarefasController $tarefasController;

        public function setUp():void
        {
            $this->tarefasController = new TarefasController();
        }

        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage
         */
        public function testInsereTarefa():void
        {
            // $this->expectException(\InvalidArgumentException::class);
            // $this->expectExceptionMessage('Ocorreu um erro ao inserir a tarefa');
            $this->assertEquals('Tarefa inserida com sucesso',$this->tarefasController->insereTarefa('murilo222','teste'));
        }

        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage
         */
        public function testAlteraTarefa():void
        {
            // $this->expectException(\InvalidArgumentException::class);
            // $this->expectExceptionMessage('Ocorreu um erro ao alterar a tarefa');
            $this->assertEquals('Tarefa alterada com sucesso',$this->tarefasController->alteraTarefa('murilo333','teste',11));
        }

        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage
         */
        public function testExcluiTarefa():void
        {
            // $this->expectException(\InvalidArgumentException::class);
            // $this->expectExceptionMessage('Ocorreu um erro ao excluir a tarefa');
            $this->assertEquals('Tarefa excluida com sucesso',$this->tarefasController->excluiTarefa(4));
        }

        public function testeSelecionaTodasTarefas():void
        {
            $this->assertGreaterThan(0,count($this->tarefasController->selecionaTodasTarefas(300,1)));
        }
    }
?>