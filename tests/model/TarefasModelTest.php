<?php
    namespace cadastroTarefasTest\TarefasModelTest;

    use PHPUnit\Framework\TestCase;
    use \banco\DataBase;
    use \cadastroTarefas\model\TarefasModel;

    class TarefasModelTest extends TestCase{

        protected DataBase $database;
        protected TarefasModel $tarefasModel;

        public function setUp():void
        {   
            $this->tarefasModel = new TarefasModel();
        }
        public function testInsereTarefa():void
        {
            $this->assertTrue($this->tarefasModel->insereTarefa('testTarefa2','testeTarefa','2022-13-12'));
        }
    }

?>