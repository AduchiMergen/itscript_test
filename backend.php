<?php ob_start();

    Abstract class Node{
        private $question = '';
        private $id = null;
        private $ansvers = array();
        private $type = 'radio';

        public function __construct($args){
            $this->id=$args['id'];
            $this->question=$args['question'];
            $this->ansvers=$args['ansvers'];
            $this->type=$args['type'];
        }

        public function params(){
            return array(
                'id'=>$this->id,
                'type'=>$this->type,
                'ansvers'=>$this->ansvers,
                'question'=>$this->question
            );
        }

        public function work($args){
            return '';
        }
    }

    class RootNode extends Node{
        public function work($args){
            switch ($args['ansver']) {
                case 1:
                    $next_node = controller_get_node(2);
                    return array('next_dialog'=>$next_node->params());
                case 2:
                    $next_node = controller_get_node(1);
                    return array('next_dialog'=>$next_node->params());
                case 3:
                    $next_node = controller_get_node(2);
                    return array('next_dialog'=>$next_node->params());
                default:
                    return array('error'=>'Wrong ansver');
            }
        }
    }

    /**
    * 
    */
    class OrderNumber extends Node
    {
        public function work($args){
            
            return array('result'=>'Заказ номер '.$args['ansver'].' доставлен.');
        }
    }

    /**
    * 
    */
    class SomethingHappened extends Node
    {
        public function work($args){
            
            return array('result'=>'Как печально, то что случилось: '.$args['ansver']);
        }
    }

    function controller_get_node($id){
        $questions_file=fopen("questions.json", "r");
        $questions=json_decode(stream_get_contents($questions_file),true);
        fclose($questions_file);
        $class=$questions[$id]['class'];
        return new $class($questions[$id]);
    }

    $questions = array(
        array(
            'class' =>'RootNode',
            'id'=> 0,
            'question'=> 'По какому поводу звоните?',
            'type'=> 'radio',
            'ansvers'=> array(
                array('value'=>1,'label'=>'Звонок по поводу проблемы качества'),
                array('value'=>2,'label'=>'Вопрос по доставке'),
                array('value'=>3,'label'=>'Консультация/Другое')
            )
        ),
        array(
            'class'=>'OrderNumber',
            'id'=> 1,
            'question'=> 'Номер заказа?',
            'type'=> 'text',
            'ansvers'=> array(
                array('value'=>'','label'=>'Номер')
            )
        ),
        array(
            'class'=>'SomethingHappened',
            'id'=> 2,
            'question'=> 'Что случилось?',
            'type'=> 'text',
            'ansvers'=> array(
                array('value'=>'','label'=>'Описание проблемы')
            )
        )
    );

    session_start();
    if (isset($_REQUEST['dialog-id'])){
        $result = controller_get_node($_REQUEST['dialog-id'])->work($_REQUEST);
    } else {
        $result = controller_get_node(0)->params();
    }
    exit(json_encode($result));
    ob_end_flush();
?>