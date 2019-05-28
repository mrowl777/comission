<?php
class db_handler {

    function connect_db(){
        include __DIR__ . '/ini.php';
        
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $mysqli->query("set names utf8");

        return $mysqli;
    }

    function close_connection( $mysqli ){
        mysqli_close( $mysqli );
    }

    function put_leader( $name ){
        $query = "INSERT INTO `leaders`(`id`, `title`) VALUES ('','".$name."')";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function put_group( $title ){
        $query = "INSERT INTO `groups`(`id`, `_group`) VALUES ('','".$title."')";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function update_comment($id, $text){
        $query = "UPDATE `students` SET `comment`='".$text."' WHERE `id` = '".$id."'";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function update_mark($id, $mark){
        $query = "UPDATE `students` SET `mark`='".$mark."' WHERE `id` = '".$id."'";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function update_leader($id, $title){
        $query = "UPDATE `students` SET `leader`='".$title."' WHERE `id` = '".$id."'";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function put_student( $name, $last_name, $surname, $group, $theme, $leader, $comment ){
        $part_1 = "INSERT INTO `students`(`id`, `f_name`, `l_name`, `s_name`, `_group`, `leader`, `theme`, `comment`, `mark`) VALUES ";
        $part_2 = "('','".$name."','".$last_name."','".$surname."','".$group."','".$leader."','".$theme."','".$comment."','0')";
        $db_helper = $this->connect_db();
        $db_helper->query( $part_1 . $part_2 );
        $this->close_connection( $db_helper );
        return;
    }

    function load_groups(){
        $query = "SELECT * FROM `groups` WHERE 1";
        $db_helper = $this->connect_db();
        $data = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $data ) === 0;

        if( $count ){
            return false;
        }
        $groups = [];
        while ($row = $data->fetch_assoc()) {
            $groups[$row["id"]] = $row["_group"];
        }
        return $groups;
    }

    function load_leaders(){
        $query = "SELECT * FROM `leaders` WHERE 1";
        $db_helper = $this->connect_db();
        $data = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $data ) === 0;

        if( $count ){
            return false;
        }
        $leaders = [];
        while ($row = $data->fetch_assoc()) {
            $leaders[$row["id"]] = $row["title"];
        }
        return $leaders;
    }

    function load_stud_list(){
        $query="SELECT * FROM `students` WHERE 1";
        $db_helper = $this->connect_db();
        $object = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $object ) === 0;
        $groups = $this->load_groups();
        $leaders = $this->load_leaders();
        if( $count ){
            return false;
        }

        $students = [];
        while ($row = $object->fetch_assoc()) {
            $title = $row["l_name"] . " " . $row["f_name"] . " " . $row["s_name"];
            $students[] = [
                'id' => $row["id"],
                'title' => $title,
                'group' => $groups[$row["_group"]],
                'leader' => $leaders[$row["leader"]],
                'theme' => $row["theme"],
                'comment' => $row["comment"],
                'mark' => $row['mark'],
            ];
        }

        return $students;
    }
}

?>