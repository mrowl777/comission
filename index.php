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
$dates = $_handler->get_dates();
$leaders = $_handler->get_leaders();
$comission = $_handler->get_comission();

if( isset($_GET['action']) ){
    switch ($_GET['action']) {
        case 'load_students':
            $students_list = $_handler->get_students_list($_GET['group_id'], $_GET['date']);
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
    <link rel="shortcut icon" href="fav.png" type="image/x-icon">
    <script src="js/jquery-3.4.0.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
<div class="header <?php if($need_login){ echo 'hidden';} ?>">
    <img src="logo.svg">    
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
            <div class="first_step">
                <p>Выберите дату</p>
                <select id="choose_date">
                    <option value="0" selected disabled>Дата</option>
                    <?php
                    if($dates){
                        foreach ( $dates as $date ){
                            $normal_date = date("d.m.Y", strtotime($date));
                            echo "<option value='".$date."'>".$normal_date."</option>";
                        }
                    }
                    ?>
                </select>
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
            <input type="date" title="Дата защиты" id="new_student_date" >
            <input type="text" id="new_student_comment" placeholder="Комментарий">
            <button id="new_stud_add">Добавить</button>

            <div id="new_group" class=" <?php if(!$admin){ echo 'hidden'; } ?>">
                <input type='text' placeholder="Введите номер новой группы">
                <button class='submit'>Добавить</button>
            </div>

            <div id="new_leader" class=" <?php if(!$admin){ echo 'hidden'; } ?>">
                <input type='text' placeholder="Введите ФИО нового руководителя">
                <button class='submit'>Добавить</button>
            </div>

            <div id="new_user" class=" <?php if(!$admin){ echo 'hidden'; } ?>">
                <input type='text' placeholder="Введите фамилию нового экзаменатора">
                <button class='submit'>Добавить</button>
            </div>

            <div class='comission_block'>
                <select id="comission">
                    <option selected disabled>Редактировать комиссию</option>
                    <?php
                    if($comission){
                        foreach ( $comission as $key => $each ){
                            $marked = '';
                            if( $each['type'] == 1 ){
                                $marked = 'leader_pck';
                            }
                            echo "<option class='".$marked."' value='".$key."'>".$each['name']."</option>";
                        }
                    }
                    ?>   
                    <option value="create_new">Добавить члена комиссии</option>
                </select>
                <input type='text' id='comission_field' placeholder="Введите ФИО члена комиссии" class='hidden'>
                <div class='point_block hidden'> <p>Это председатель?</p><input type="checkbox" id='is_main'> </div>
                <button class='comission_submit'>Сохранить</button>
            </div>
        </div>

    </div>

    <div class="second_step <?php if(!$group_selected || $passed ){ echo "hidden"; }  ?>">
 
    <div class="left_panel">
    <?php
        $normal_date = date("d.m.Y", strtotime($_GET['date']));
        $comission_html = '<h2>Члены комиссии: </h2>';
        $main = '<h2>Председатели: </h2>';
        echo "<h1>Дата: " . $normal_date . "</h1> <br/>";
        if($comission){
            $preds = [];
            foreach ( $comission as $each ){
                $marked = '';
                if( $each['type'] == 1 ){
                    $preds[] = $each['name'];
                    continue;
                }
                $comission_html.= "<p>".$each['name']."</p>";
            }
            foreach ( $preds as $each ){
                $main.= "<p>".$each."</p>";
            }
            echo $main . "<br/>";
            echo $comission_html;
        }
    ?>   
        
    </div> 
    
    <div class="right_side">
        <?php
            echo "<h2>". $_GET['group_title'] ."</h2>";

        if(!$students_list){
            echo "<h2>По заданному запросу не найдено данных.</h2>";
        }else{
            echo "<table class='tftable' border='1'>";
            echo "<tr><th>№</th><th>ФИО</th><th>Оценка</th><th>Комментарий</th></tr>";
            $i = 1;
            foreach ($students_list as $id => $student) {
                $date = $_GET['date'];
                $group = $_GET['group_id'];
                $group_title = $_GET['group_title'];
                echo "<tr><td>".$i."</td>";
                echo "<td><a href='?student_id=".$id."&group_id=".$group."&group_title=".$group_title."&date=".$date."&action=get_data' >".$student['title']."</a></td>";
                echo "<td>".$student['mark']."</td>";
                echo "<td>".$student['comment']."</td></tr>";
                $i++;
            }

            echo "</table>";
        }
        ?>
    </div>

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
    <?php 
        if( $passed ) {
            $date = $_GET['date'];
            $group = $_GET['group_id'];
            $group_title = $_GET['group_title'];
            echo '<a href="?group_id='.$group.'&group_title='.$group_title.'&date='.$date.'&action=load_students" class="start_link">Назад</a>';
        } 
    ?>
</div>

<div id="login" class="<?php if(!$need_login){ echo 'hidden';} ?>">
    <div class="line"><p>Логин:</p><input type="text" name="username"  placeholder="Введите логин"></div>
    <div class="line"><p>Пароль:</p><input type="password" name="password" placeholder="Введите пароль"></div>
    <button class="login_btn" >ВОЙТИ</button>
</div>
<button id="logout" class="<?php if($need_login){ echo 'hidden';} ?>">Выйти</button>
</body>