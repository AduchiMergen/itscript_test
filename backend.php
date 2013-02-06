<?php ob_start();
    session_start();
    $dialog = array(
        'id'=> 0,
        'question'=> 'По какому поводу звоните?',
        'type'=> 'radio',
        'ansvers'=> array(
            array('value'=>1,'label'=>'Звонок по поводу проблемы качества'),
            array('value'=>2,'label'=>'Вопрос по доставке'),
            array('value'=>3,'label'=>'Консультация/Другое')
        )
    );
    $dialog1 = array(
        'id'=> 1,
        'question'=> 'Номер заказа?',
        'type'=> 'text',
        'ansvers'=> array(
            array('value'=>'','label'=>'Номер')
        )
    );
    if (isset($_REQUEST['dialog-id'])){
        echo json_encode($dialog1);
    } else {
        echo json_encode($dialog);    
    }
    ob_end_flush();
?>