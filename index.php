<?php

include 'handler.php';

$_handler = new Handler();
$students = $_handler->get_students();
$groups = $_handler->get_groups();
$leaders = $_handler->get_leaders();
?>

<head>
    <title>Дипломная комиссия</title>
    <link href="css/index.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="icon.png" type="image/x-icon">
    <script src="js/jquery-3.4.0.js"></script>
    <script src="js/index.js"></script>
</head>
<body>

<div class="content">
    <div class="block">
        <input type="text" id="new_leader" placeholder="Новый руководитель">
        <button class="submit">OK</button>
    </div>
    <div class="block">
        <input type="text" id="new_group" placeholder="Новая группа">
        <button class="submit">OK</button>
    </div>
    <div class="new_student">
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
        <button id="new_stud_add" class="submit">OK</button>
    </div>
</div>

<div class="d_table <?php if(!$students){ echo "hidden"; }  ?>">
    <table class="tftable" border="1">
    <tr>
        <th>ФИО</th>
        <th>Группа</th>
        <th>Тема</th>
        <th>Руководитель</th>
        <th>Комментарий</th>
        <th>Оценка</th>
    </tr>
    <?php
    foreach( $students as $student ){
        echo "<tr id='".$student['id']."'>";
        echo "<td>" . $student['title'] . "</td>";
        echo "<td>" . $student['group'] . "</td>";
        echo "<td>" . $student['theme'] . "</td>";
        echo "<td>" . $student['leader'] . "</td>";
        echo "<td>" . $student['comment'] . "</td>";
        echo "<td>" . $student['mark'] . "</td>";
        echo "</tr>";
    }
    ?>
    </table>
</div>
</body>