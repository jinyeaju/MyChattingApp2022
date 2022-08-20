<?php
    //http://localhost/chattingApp/register_ok.php

    $id = $_POST["id"];
    $pass = $_POST["pass"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $register_day = date("Y-m-d");

    include "./db_con.php";
    //DB에 동일한 아이디가 존재하는지를 찾는다.
    $sql = "select * from register where id = '$id'";
    $result = mysqli_query($con, $sql);
    $total_record = mysqli_num_rows($result);
    if($total_record){ //동일한 아이디가 존재하는 상태
        echo("
            <script>
                alert('동일한 아이디가 존재합니다.\n다른 아이디로 입력 바랍니다.');
                history.go(-1);
            </script>
        ");
    }else{ //입력한 아이디가 새로운 아이디일 때
        //id, pass, name, email, register_day	
        $sql = "insert into register (id, pass, name, email, register_day) value('$id', '$pass', '$name', '$email', '$register_day')";
        mysqli_query($con, $sql);

        echo("
            <script>
                location.href = './main.html';
            </script>
        ");

    }
?>