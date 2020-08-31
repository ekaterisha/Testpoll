<?php 
$page = '
<html>
    <head><meta charset="utf-8">
        <title> Результаты тестирования </title>
        
        <link href="http://mybootstrap.ru/wp-content/themes/clear-theme/styles/bootstrap.css" rel="stylesheet">
        <link href="http://mybootstrap.ru/wp-content/themes/clear-theme/styles/bootstrap-responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="rin/CSS/testing.css">

    </head>
<body> 
<div class="container-narrow">';
if (isset($dataProvider['user_result'])){
    $page .= '

    <h1 class="muted"> Результаты теста №'.$this->model->test_id.'</h1>
    <hr>';

    $page .= '
    <div class="user"> 
    <p><strong>'.$dataProvider['user_result'][$this->model->test_id]['user'][$this->model->user_id]['user_name'].'</strong> ('
                .$dataProvider['user_result'][$this->model->test_id]['user'][$this->model->user_id]['user_mail'].'), ваши результаты следующие: </p>
    </div>';

    foreach ($dataProvider['user_result'][$this->model->test_id]['questions_array'] as $qkey => $question){
       
       $point = ($question['point'] == 1)? '+1 балл' : 'неверно';
       $page .= '<div class="point"> <p><strong> Вопрос №'.$qkey.':</strong> '.$point.'</p>';
       $page .= '</div>';    
    }

    $page .= '
    <div class="user"> 
    <p> Всего правильных ответов:  <strong> '
                .$dataProvider['user_result'][$this->model->test_id]['cnt_right'].'</strong> </p>
    </div>
    ';
}

$page .= '
<hr>
<h3 class="muted"> Статистика по Тесту №'.$this->model->test_id.'</h3>
<table>
 <tr> 
  <th>&nbsp;</th>
  <th>Общее количество ответивших </th>
  <th>Кол-во верно ответивших</th>
  <th>Процент верно ответивших</th>
  </tr>';
    if (isset($dataProvider['one_test_result'])){
        $n = 1;
        foreach ($dataProvider['one_test_result'] as  $test){
                $page .= '
                <tr> 
                <td> Вопрос №'.$n++.'</td>
                <td>'.$test['cnt'].'</td>
                <td>'.$test['cnt_point'].'</td>
                <td>'.$test['percent_ques'].'%</td>
                </tr>
                ';
        }
    }
  $page .= '
 </table>
';
$page .= '
<hr>
<h3 class="muted"> Статистика по тестам </h3>
<table>
 <tr> 
  <th>&nbsp;</th>
  <th>Кол-во вопросов </th>
  <th>Общее количество ответивших </th>
  <th>Кол-во набравших мах балл</th>
  <th>Процент набравших мах балл</th>
  <th>Минимальный балл </th>
  <th>Максимальный балл </th>
 </tr>';
if (isset($dataProvider['tests_result'])){
    foreach ($dataProvider['tests_result'] as  $tests){
        $page .= '
        <tr> 
        <td>'.$tests['test_name'].'</td>
        <td>'.$tests['cnt'].'</td>
        <td>'.$tests['user_cnt'].'</td>
        <td>'.$tests['cnt_max_u'].'</td>
        <td>'.$tests['percent'].'%</td>
        <td>'.$tests['min_point'].'</td>
        <td>'.$tests['max_point'].'</td>
        </tr>
        ';
    }
}
 $page .= '
 </table>
';


$page .= '
<hr>
<div class="footer"> 
<p> ООО Рина </p>
<p> E-mail: ekaterisha48@gmail.com  </p>
</div> 
</div>
<!-- Подключаем jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Подключаем плагин Popper -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<!-- Подключаем Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous" ></script>

</body>
</html>';
echo $page;

?>