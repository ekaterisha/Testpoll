<?php namespace Testpoll\rin\Models;
class ResultStatModel {

    public $data;
    public $model;
    public $test_id;
    public $user_id;
    public $mysqli;



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
        $this->user_id = isset($_GET['user_id'])?$_GET['user_id']:0;
        if ($this->user_id != 0) {

            if ($this->conn_status) {
                $res = $this->mysqli->query("call user_result(".$this->test_id.", ".$this->user_id.")");
            }
            if ($res) {
                $cnt_right = 0;
                while ($row = $res->fetch_assoc()){

                    $test_ques_id = $row['test_ques_id'];
                            
                    $this->data['user_result'][$this->test_id]['name'] = $row['test_name'];
                    $this->data['user_result'][$this->test_id]['user'][$this->user_id]['user_name'] = $row['user_name'];
                    $this->data['user_result'][$this->test_id]['user'][$this->user_id]['user_mail'] = $row['user_mail'];
                    $this->data['user_result'][$this->test_id]['questions_array'][$test_ques_id]['name'] = $test_ques_id;
                    $this->data['user_result'][$this->test_id]['questions_array'][$test_ques_id]['point'] = $row['point'];
                                    
                    if ($row['point'] == '1') {
                        $cnt_right++; 
                    }
                    $this->data['user_result'][$this->test_id]['cnt_right'] = $cnt_right;
                }
                $res->close();
                $this->mysqli->next_result();
            } 
        } 
        if ($this->conn_status) {
            $res_all_tests = $this->mysqli->query("call total_result();");
        }
        if ($res_all_tests){
            while ($row = $res_all_tests->fetch_assoc()){
                $this->data['tests_result'][] = $row;
            }
            $res_all_tests->close();
            $this->mysqli->next_result();
        }

        if ($this->conn_status) {
            $res_one_tests = $this->mysqli->query("call test_result(".$this->test_id.");");
        }
        if ($res_one_tests){
            while ($row = $res_one_tests->fetch_assoc()){
                $this->data['one_test_result'][] = $row;
            }
            //var_dump($this->data['one_test_result']);
            $res_one_tests->close();
            $this->mysqli->next_result();
        }


    }
}
