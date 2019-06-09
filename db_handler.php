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

    function put_student( $name, $last_name, $surname, $group, $theme, $leader, $comment, $date ){
        $part_1 = "INSERT INTO `students`(`id`, `f_name`, `l_name`, `s_name`, `_group`, `leader`, `theme`, `comment`, `mark`, `date`) VALUES ";
        $part_2 = "('','".$name."','".$last_name."','".$surname."','".$group."','".$leader."','".$theme."','".$comment."','0', '".$date."')";
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

    function load_students_list( $group ){
        $query="SELECT * FROM `students` WHERE `_group` = '".$group."'";
        $db_helper = $this->connect_db();
        $object = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $object ) === 0;
        if( $count ){
            return false;
        }

        $students = [];
        while ($row = $object->fetch_assoc()) {
            $title = $row["l_name"] . " " . $row["f_name"] . " " . $row["s_name"];
            $students[$row["id"]] = $title;
        }

        return $students;
    }

    function load_student_data( $sid ){
        $query="SELECT * FROM `students` WHERE `id` = '".$sid."'";
        $db_helper = $this->connect_db();
        $object = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $object ) === 0;
        $groups = $this->load_groups();
        $leaders = $this->load_leaders();
        if( $count ){
            return false;
        }

        $object = $object->fetch_assoc();
        $title = $object["l_name"] . " " . $object["f_name"] . " " . $object["s_name"];
        $student = [
            'id' => $object["id"],
            'title' => $title,
            'group' => $groups[$object["_group"]],
            'leader' => $leaders[$object["leader"]],
            'theme' => $object["theme"],
            'comment' => $object["comment"],
            'mark' => $object['mark'],
        ];

        return $student;
    }

    function add_user( $username, $pass ){
        $db_helper = $this->connect_db();
        if(!$this->check_free_name( $username )){
            $username = $username . "_" . rand(0, 100);
        }
        $pass_md = md5(md5($pass)); 
        $query = "INSERT INTO `users`(`id`, `login`, `pass`, `admin` ) VALUES ( '','".$username."','".$pass_md."','0' )";
        $result = $db_helper->query( $query );
        return array( $username, $pass );
    }

    function check_free_name( $name ){
        $db_helper = $this->connect_db();
        $query = "SELECT * FROM users WHERE `login` = '" . $name . "'";
        $result = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $result ) === 0;
        return $count;
    }

    function check_password($login, $password){
        $query = "SELECT `pass`, `admin` FROM `users` WHERE `login` = '".$login."'";
        $db_helper = $this->connect_db();
        $data = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $data ) === 0;

        if( $count ){
            return 'not_found';
        }

        $user = $data->fetch_assoc();

        if($user['pass'] !== md5(md5($password))){
            return 'wrong_password';
        }

        $rights = $user['admin'] == 1 ? 'admin' : 'user';

        return $rights;
    }

    function create_com_user( $title, $dir ){
        $query = "INSERT INTO `comission_list`(`id`, `title`, `is_dir`) VALUES ('','".$title."','".$dir."')";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function update_com_user( $title, $type, $id ){
        $query = "UPDATE `comission_list` SET `title`='".$title."', `is_dir`='".$type."' WHERE `id` = '". $id ."'";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function delete_com_user( $id ){
        $query = "DELETE FROM `comission_list` WHERE `id` = '".$id."'";
        $db_helper = $this->connect_db();
        $db_helper->query( $query );
        $this->close_connection( $db_helper );
        return;
    }

    function get_com_list(){
        $query="SELECT * FROM `comission_list` WHERE 1";
        $db_helper = $this->connect_db();
        $object = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $object ) === 0;
        if( $count ){
            return false;
        }

        $comission = [];
        while ($row = $object->fetch_assoc()) {
            $comission[$row["id"]] = [
                'name' => $row['title'],
                'type' => $row['is_dir']
            ];
        }

        return $comission;
    }

    function get_dates(){
        $query = "SELECT DISTINCT `date` FROM `students` WHERE `date` != ''";
        $db_helper = $this->connect_db();
        $object = $db_helper->query( $query );
        $this->close_connection( $db_helper );
        $count = mysqli_num_rows( $object ) === 0;
        if( $count ){
            return false;
        }
        $dates = [];
        while ($row = $object->fetch_assoc()) {
            $dates[] = $row['date'];
        }

        return $dates;
    }
}

?>