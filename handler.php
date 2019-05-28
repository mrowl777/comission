<?php

include 'db_handler.php';

class Handler extends db_handler {

    function add_student(){
        $name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $surname = $_POST['surname'];
        $group = $_POST['group'];
        $theme = $_POST['theme'];
        $leader = $_POST['leader'];
        $comment = $_POST['comment'];
        $this->put_student( $name, $last_name, $surname, $group, $theme, $leader, $comment );
        die( json_encode(['result' => 'ok']) );
    }

    function edit_comment(){
        $id = $_POST['id'];
        $text = $_POST['text'];
        $this->update_comment($id, $text);
    }

    function add_leader(){
        $name = $_POST['title'];
        $this->put_leader( $name );
        die( json_encode(['result' => 'ok']) );
    }

    function add_group(){
        $title = $_POST['title'];
        $this->put_group( $title );
        die( json_encode(['result' => 'ok']) );
    }

    function get_groups(){
        $groups = $this->load_groups();
        return $groups;
    }

    function get_leaders(){
        $leaders = $this->load_leaders();
        return $leaders;
    }

    function get_students(){
        $students = $this->load_stud_list();
        if( !$students ){
            return false;
        }
        return $students;
    }
}

$handler = new Handler();

if( isset( $_POST['action'] ) ){
    switch ( $_POST['action'] ) {
        case 'add_student':
            $handler->add_student();
            break;
        case 'add_leader':
            $handler->add_leader();
            break;
        case 'add_group':
            $handler->add_group();
            break;
        case 'edit_comment':
            $handler->edit_comment();
            break;
        default:
            die();
            break;
    }
}
?>