<?php ob_start();

define('DIALOG_SOURCE_FILE', "questions.json");
define('MYSQL_USER',"user");
define('MYSQL_PWD',"password");
define('MYSQL_DB',"itscript_test");

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

    class OrderNumber extends Node
    {
        public function work($args){
            
            return array('result'=>'Заказ номер '.$args['ansver'].' доставлен.');
        }
    }

    class SomethingHappened extends Node
    {
        public function work($args){
            
            return array('result'=>'Как печально, то что случилось: '.$args['ansver']);
        }
    }

    function controller_get_node($id){
        $questions_file=fopen(DIALOG_SOURCE_FILE, "r");
        $questions=json_decode(stream_get_contents($questions_file),true);
        fclose($questions_file);
        if (isset($questions[$id])){
            $class=$questions[$id]['class'];
            return new $class($questions[$id]);
        } else {
            return null;
        }
    }

    function log_to_db($client,$dialog_id,$request,$response){
        require_once 'lib/safemysql.class.php';
        $opts = array(
            'user'    => MYSQL_USER,
            'pass'    => MYSQL_PWD,
            'db'      => MYSQL_DB
        );
        $db = new SafeMySQL($opts);
        $db->query("CREATE TABLE IF NOT EXISTS `logs` (`client_id` VARCHAR(50),`dialog_id` INT, `request` TEXT, `response` TEXT)");
        $db->query("INSERT INTO `logs` VALUES (?s,?i,?s,?s)",$client,$dialog_id,$request,$response);
    }

    session_start();
    if (isset($_REQUEST['dialog-id'])){
        $node = controller_get_node($_REQUEST['dialog-id']);
        if ($node){
            $result = $node->work($_REQUEST);
        } else {
            $result = array('error'=>'node not found');
        }
        log_to_db(session_id(),$_REQUEST['dialog-id'],json_encode($_REQUEST),json_encode($result));
    } else {
        session_regenerate_id(true);
        $result = array(
            'session_id'=>session_id(),
            'node'=>controller_get_node(0)->params()
        );
        log_to_db(session_id(),0,'New client',json_encode($result));
    }
    ob_end_flush();
    exit(json_encode($result));
?>