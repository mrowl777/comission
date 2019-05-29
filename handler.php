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

    function edit_mark(){
        $id = $_POST['id'];
        $mark = $_POST['mark'];
        $this->update_mark($id, $mark);
    }

    function edit_leader(){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $this->update_leader($id, $title);
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

    function get_students_list( $group ){
        return $this->load_students_list( $group );
    }

    function get_student_data( $sid ){
        return $this->load_student_data( $sid );
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
        case 'update_mark':
            $handler->edit_mark();
            break;
        case 'update_leader':
            $handler->edit_leader();
            break;
        default:
            die();
            break;
    }
}
?>