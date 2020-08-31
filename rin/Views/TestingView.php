<?php 
$page = '<html>';
$page .= '<head><meta charset="utf-8">
<title> Форма тестирования </title>

<link href="http://mybootstrap.ru/wp-content/themes/clear-theme/styles/bootstrap.css" rel="stylesheet">
<link href="http://mybootstrap.ru/wp-content/themes/clear-theme/styles/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="rin/CSS/testing.css">
</head>

<body>
<div class="container-narrow">
';
if (isset($dataProvider)){
    $page .= '<div class="masthead">
    <h1 class="muted">'.$dataProvider[$this->model->test_id]['name'].'</h1>
    </div>
    <hr>';
    $page .= '<div class="jumbotron"> <form method="post" id="testing_form" validated="false"  test_id="'.$this->model->test_id.'" action="index.php?r=Testing&action=create&test_id='.$this->model->test_id.'">';
    $n = 1;
    foreach ($dataProvider[$this->model->test_id]['questions_array'] as $qkey => $question){
        $page .= '<div class="question"> <p><strong> Вопрос №'.$n++.':</strong> '.$question['name'].'</p>';
        foreach($question['answers_array'] as $key => $answer){
            $value = $answer['answer_type_name'] == 'text' ? '' : 'value="'.$answer['name'].'"';
            $name = $answer['answer_type_name'] == 'radio' ? 'name="'.$qkey.'['.$key.']"' : 'name="'.$qkey.'['.$key.']"'; //  'name = "'.$qkey.'[]"'
            $page .= '<p><input type="'.$answer['answer_type_name'].'" '.$name.' '.$value.'> '.$answer['name'].'</p>';
        }
        $page .= '</div>';    
    }
    $page .= '

    <p><b>Ваше имя:</b><br>
    <input type="text" name="fio" placeholder="Александров Александр Александрович" size="40" required></p>
    <p><b>Электронная почта:</b><Br>
    <input type="email" name="email" placeholder="example@gmail.com" size="40" ><Br>
    </p>
    <button class="btn btn-success" type="submit">Отправить результат</button>
    <button class="btn btn-success" type="button" onclick="window.location.href=\'index.php?r=ResultStat&test_id='.$this->model->test_id.'\'">Посмотреть статистику</button>
    </form>';
} else {
    $page .= '<h1> Тест №'.$this->model->test_id.' не существует </h1>';
}


$page .= '
</div>
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

<script src="rin/JS/func.js"></script>
</html>';
echo $page;

?>