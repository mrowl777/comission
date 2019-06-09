<?php

include 'handler.php';

$need_login = true;

if ( isset($_COOKIE["rights"]) ){
    $hash = hash('sha256', 'admin' );
    $admin = $_COOKIE["rights"] == $hash ? true : false;
    $need_login = false;
}

$_handler = new Handler();
$groups = $_handler->get_groups();
$leaders = $_handler->get_leaders();

if( isset($_GET['action']) ){
    switch ($_GET['action']) {
        case 'load_students':
            $students_list = $_handler->get_students_list($_GET['group_id']);
            $group_selected = true;
            break;
        case 'get_data':
            $student_data = $_handler->get_student_data($_GET['student_id']);
            $group_selected = true;
            $passed = true;
            break;
        default:
            break;
    }
}
?>

<head>
    <title>Дипломная комиссия</title>
    <link href="css/index.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="icon.png" type="image/x-icon">
    <script src="js/jquery-3.4.0.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
<div class="header">
    <img src="icon.png">    
    <p>Секретарь комиссии ГЭК</p>
    <span id="doc_time"></span>
</div>
<div class="content <?php if($need_login){ echo 'hidden';} ?>">

    <div class="new_panel <?php if($group_selected){ echo "hidden"; }  ?>">
        <div class="left_panel">
            <div class="first_step">
                <p>Выберите группу</p>
                <select id="choose_group">
                    <option value="0" selected disabled>Группа</option>
                    <?php
                    if($groups){
                        foreach ( $groups as $key => $group ){
                            echo "<option value='".$key."'>".$group."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div id="new_group" class="first_step <?php if(!$admin){ echo 'hidden'; } ?>">
                <p>Создать группу</p>
                <input type='text' placeholder="Введите номер группы">
                <button class='submit'>OK</button>
            </div>

            <div id="new_leader" class="first_step <?php if(!$admin){ echo 'hidden'; } ?>">
                <p>Создать экзаменатора</p>
                <input type='text' placeholder="Введите ФИО">
                <button class='submit'>OK</button>
            </div>
        </div>

        <div class="new_student <?php if(!$admin){ echo 'hidden'; } ?>">
            <input type="text" id="new_student_l_name" placeholder="Фамилия">
            <input type="text" id="new_student_f_name" placeholder="Имя">
            <input type="text" id="new_student_s_name" placeholder="Отчество">
            <select id="new_student_group">
                <option selected disabled>Группа</option>
                <?php
                if($groups){
                    foreach ( $groups as $key => $group ){
                        echo "<option value='".$key."'>".$group."</option>";
                    }
                }
                ?>
            </select>
            <input type="text" id="new_student_theme" placeholder="Тема">
            <select id="new_student_leader">
                <option selected disabled>Руководитель</option>
                <?php
                if($leaders){
                    foreach ( $leaders as $key => $leader ){
                        echo "<option value='".$key."'>".$leader."</option>";
                    }
                }
                ?>   
            </select>
            <input type="text" id="new_student_comment" placeholder="Комментарий">
            <button id="new_stud_add">Добавить</button>
        </div>

    </div>

    <div class="second_step <?php if(!$group_selected || $passed ){ echo "hidden"; }  ?>">
    <?php
    if($students_list){
        echo "<h2>Выберите студента:</h2><hr/>";
    }else{
        echo "<h2>В этой группе нет ни одного студента</h2>";
    }
    ?>
    <?php
    if($students_list){
        foreach ($students_list as $id => $title) {
            echo "<div class='each_student'><a href='?student_id=".$id."&action=get_data' >".$title."</a></div>";
         }
    }
    ?>
    </div>
    
    <div class="student_info <?php if(!$passed ){ echo "hidden"; }  ?>">
        <div class="student_block" id='<?php  echo $student_data['id'] ?>'>
            <div class="student_title">
                <p><?php  echo $student_data['title'] ?></p>
            </div>

            <div class="mblock">
                <p>Группа:</p>
                <div class="student_group"><?php  echo $student_data['group'] ?></div>
            </div>

            <div class="mblock">
                <p>Тема:</p>
                <div class="student_theme"><?php  echo $student_data['theme'] ?></div>
            </div>

            <div class="mblock">
                <p>Руководитель:</p>
                <div class="student_leader"><?php  echo $student_data['leader'] ?></div>
            </div>
            <div class="mblock">
                <p>Оценка: </p>
                <?php
                    $none = $student_data['mark'] == 0 ? 'selected' : '';
                    $two = $student_data['mark'] == 2 ? 'selected' : '';
                    $three = $student_data['mark'] == 3 ? 'selected' : '';
                    $four = $student_data['mark'] == 4 ? 'selected' : '';
                    $five = $student_data['mark'] == 5 ? 'selected' : '';
                    echo "<select class='student_mark'>";
                    echo "<option value='0' ".$none.">Отсутствует</option>";
                    echo "<option value='2' ".$two.">2</option>";
                    echo "<option value='3' ".$three.">3</option>";
                    echo "<option value='4' ".$four.">4</option>";
                    echo "<option value='5' ".$five.">5</option>";
                    echo "</select>";
                ?>
            </div>


            <div class="comment">
                <p>Комментарий:</p>
                <textarea id="_comment"><?php  echo $student_data['comment'] ?></textarea>
            </div>
        </div>
    </div>
    <a href="/comission" class="start_link <?php if(!$group_selected && !$passed) {echo 'hidden';} ?>">В начало</a>
</div>

<div id="login" class="<?php if(!$need_login){ echo 'hidden';} ?>">
    <div class="line"><p>Логин:</p><input type="text" name="username"  placeholder="Введите логин"></div>
    <div class="line"><p>Пароль:</p><input type="password" name="password" placeholder="Введите пароль"></div>
    <button class="login_btn" >ВОЙТИ</button>
</div>
<button id="logout" class="<?php if($need_login){ echo 'hidden';} ?>">Выйти</button>
</body>