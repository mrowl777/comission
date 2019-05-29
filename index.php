<?php

include 'handler.php';

$_handler = new Handler();
$students = $_handler->get_students();
$groups = $_handler->get_groups();
$leaders = $_handler->get_leaders();

if( isset($_GET['action']) ){
    switch ($_GET['action']) {
        case 'load_students':
            $students_list = $_handler->get_students_list($_GET['group_id']);
            $group_selected = true;
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

<div class="content">

    <div class="first_step <?php if($group_selected){ echo "hidden"; }  ?>">
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

    <div class="second_step <?php if(!$group_selected){ echo "hidden"; }  ?>">
    <?php
        foreach ($students_list as $id => $title) {
           echo "<div><a href='".$id."' >".$title."</a></div>";
        }
    ?>
    </div>


    <!-- <div class="new_student <?php //if($students){ echo "hidden"; }  ?>">
        <input type="text" id="new_student_l_name" placeholder="Фамилия">
        <input type="text" id="new_student_f_name" placeholder="Имя">
        <input type="text" id="new_student_s_name" placeholder="Отчество">
        <select id="new_student_group">
            <option selected disabled>Группа</option>
            <?php
            // if($groups){
                // foreach ( $groups as $key => $group ){
                    // echo "<option value='".$key."'>".$group."</option>";
                // }
            // }
            ?>
        </select>
        <input type="text" id="new_student_theme" placeholder="Тема">
        <select id="new_student_leader">
            <option selected disabled>Руководитель</option>
            <?php
            // if($leaders){
                // foreach ( $leaders as $key => $leader ){
                    // echo "<option value='".$key."'>".$leader."</option>";
                // }
            // }
            ?>
            
        </select>
        <input type="text" id="new_student_comment" placeholder="Комментарий">
        <button id="new_stud_add" class="submit">OK</button>
    </div> -->
</div>
<!--
<div class="d_table <?php if(!$students){ echo "hidden"; }  ?>">
    <table class="tftable" border="1">
    <tr>
        <th>ФИО</th>
        <th>
            <div id="gr">Группа</div>
            <div class="plus_block"><img id="gr_plus_btn" style="width: 20px;" src="plus.png"/></div>
            <div class="hidden gr_block">
                <input type="text" id="new_group" placeholder="Новая группа">
                <button class="submit">OK</button>
            </div>
        </th>
        <th>Тема</th>
        <th>
            <div id="cur">Руководитель</div>
            <div class="plus_block"><img id="cur_plus_btn" style="width: 20px;" src="plus.png"/></div>
            <div class="hidden cur_block">
                <input type="text" id="new_leader" placeholder="Новый руководитель">
                <button class="submit">OK</button>
            </div>
        </th>
        <th>Комментарий</th>
        <th>Оценка</th>
    </tr>
    <tr style="background: wheat;">
    <td><input class="intable" type="text" id="table_name" placeholder="ФИО"></td>
    <td>
        <select id="table_group" class="intable" >
            <option selected disabled>Группа</option>
            <?php
            // if($groups){
            //     foreach ( $groups as $key => $group ){
            //         echo "<option value='".$key."'>".$group."</option>";
            //     }
            // }
            ?>
        </select>
    </td>
    <td><input class="intable" type="text" id="table_theme" placeholder="Тема"></td>
    <td>
        <select id="table_leader" class="intable" >
            <option selected disabled>Руководитель</option>
            <?php
            // if($leaders){
            //     foreach ( $leaders as $key => $leader ){
            //         echo "<option value='".$key."'>".$leader."</option>";
            //     }
            // }
            ?>
            
        </select> 
    </td>
    <td><input class="intable" type="text" id="table_comment" placeholder="Комментарий"></td>
    <td><button id="table_add" class="submit tbtn">Добавить</button></td>
    </tr>
    <?php
    // foreach( $students as $student ){
    //     $none = $student['mark'] == 0 ? 'selected' : '';
    //     $two = $student['mark'] == 2 ? 'selected' : '';
    //     $three = $student['mark'] == 3 ? 'selected' : '';
    //     $four = $student['mark'] == 4 ? 'selected' : '';
    //     $five = $student['mark'] == 5 ? 'selected' : '';
    //     echo "<tr id='".$student['id']."'>";
    //     echo "<td>" . $student['title'] . "</td>";
    //     echo "<td>" . $student['group'] . "</td>";
    //     echo "<td>" . $student['theme'] . "</td>";
    //     echo "<td><select class='update_leader'>";
    //     foreach ( $leaders as $key => $leader ){
    //         $status = $leader == $student['leader'] ? 'selected' : '';
    //         echo "<option value='".$key."' ".$status.">".$leader."</option>";
    //     }
    //     echo "</select></td>";
    //     echo "<td><input type='text' class='edit_comment' value='".$student['comment']."'></td>";
    //     echo "<td><select class='update_mark'>";
    //     echo "<option value='0' ".$none.">Отсутствует</option>";
    //     echo "<option value='2' ".$two.">2</option>";
    //     echo "<option value='3' ".$three.">3</option>";
    //     echo "<option value='4' ".$four.">4</option>";
    //     echo "<option value='5' ".$five.">5</option>";
    //     echo "</select></td>";
    //     echo "</tr>";
    // }
    ?>
    </table>
</div>
-->
</body>