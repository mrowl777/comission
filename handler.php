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
        $last_name = $_POST['last_name'];
        $login = $this->build_login( $last_name );
        $password = $this->generate_password();
        $this->put_leader( $name );
        list( $db_user, $db_pass ) = $this->add_user( $login, $password );
        $result = [
            'result' => 'leader_created',
            'login' => $db_user,
            'password' => $db_pass
        ];
        die( json_encode($result) );
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

    function try_au(){
        $login = $_POST['login'];
        $password = $_POST['password'];
        $resp = $this->check_password($login, $password);
        if($resp == 'not_found'){
            die( json_encode(['result' => 'not_found']) );
        }
        if($resp == 'wrong_password' ){
            die( json_encode(['result' => 'wrong_password']) );
        }
        $hash = hash('sha256', $resp );
        setcookie( 'rights', $hash, 0 );
        die( json_encode(['result' => 'ok']) );
    }

    function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
            
            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    function build_login($str) {
        $str = $this->rus2translit($str);
        $str = strtolower($str);
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        $str = trim($str, "-");
        return $str;
    }

    function generate_password(){
        return rand(11111111, 99999999);
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
        case 'login':
            $handler->try_au();
            break;
        default:
            die();
            break;
    }
}
?>