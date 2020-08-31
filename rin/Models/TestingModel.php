<?php namespace Testpoll\rin\Models;
class TestingModel {

    public $data;
    public $mysqli;
    public $test_id;
    public $user_id;
    private $conn_status;

    public function __construct(){
        $this->mysqli = \Testpoll\rin\Base::mysqli();
        if (!$this->mysqli->connect_errno) {
            $this->conn_status = 1;
        } else {
            $this->conn_status = 0;
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

    }
    
    public function getData(){

        $this->test_id = isset($_GET['test_id'])?$_GET['test_id']:1;
        if ($this->conn_status) {
            $res = $this->mysqli->query("
            select * 
            from answer a
            join question q on q.question_id =a.question_id
            join answer_type at on at.answer_type_id = a.answer_type_id 
            join test_ques tq on tq.question_id = q.question_id
            join test t on t.test_id = tq.test_id
            where t.test_id = " . $this->test_id ."
            order by q.question_id
            ");
            if ($res) {
                while ($row = $res->fetch_assoc()){
                    //var_dump($row);
                    $test_id = $row['test_id'];
                    $question_id= $row['question_id'];
                    $answer_id = $row['answer_id'];
                    $this->data[$test_id]['name'] = $row['test_name'];
                    $this->data[$test_id]['questions_array'][$question_id]['name'] = $row['question_text'];
                    $this->data[$test_id]['questions_array'][$question_id]['answers_array'][$answer_id]['answer_type_name'] = $row['answer_type_name'];
                    $this->data[$test_id]['questions_array'][$question_id]['answers_array'][$answer_id]['name'] = $row['answer_text'];
                }
            }            
        }
    }
    public function setData(){
        $this->test_id = $_GET['test_id'];
        $user_mail = $_POST['email'];
        $user_name = $_POST['fio'];

        $check = "call get_user_id('".$user_mail."', '".$user_name."')";

        if ($this->conn_status) {
            $result = $this->mysqli->query($check);
            $this->user_id = $result->fetch_row()[0];
            $result->close();
            $this->mysqli->next_result();
            foreach ($_POST as $key => $value) { // добавляем ответы пользователя
                if ($key != 'fio'&& $key != 'email'){
                    foreach ($value as $k => $answer){
                        $insert = "
                        insert into response 
                        select null,
                                ".$this->user_id.",
                                tq.test_ques_id,
                                ".$k.",
                                '".$answer."'
                        from test_ques tq
                        where tq.test_id = ".$this->test_id." 
                        and tq.question_id = ".$key."";
                        $this->mysqli->query($insert);
                    }
                } 
            }
        }

    }
    public function checkData(){
        $user_mail = $_POST['email'];
        $test_id = $_POST['test_id'];

        $check = "
        select exists (
            select null
            from user u 
            join test_ques tq on tq.test_id = ".$test_id."
            join response r on r.test_ques_id = tq.test_ques_id
                            and r.user_id = u.user_id
            where user_mail = '".$user_mail."')";
        $res = $this->mysqli->query($check); // проверяем отправлял ли пользователь ответы на этот тест
        $row = $res->fetch_row(); 
        if ($row[0]) { // если пользователь отправлял
            echo 'yes';
        } else {
            echo 'no';
        }
    } 

}
?>