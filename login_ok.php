<?php
    //http://localhost/chattingApp/login_ok.php

    $id = $_POST["id"];
    $pass = $_POST["pass"];

    include "./db_con.php";

    $sql = "select * from register where id='$id'";
    $result = mysqli_query($con, $sql);
    $total_record = mysqli_num_rows($result);

    if(!$total_record){
        echo("
            <script>
                alert('등록되지 않은 아이디 입니다.');
                history.go(-1);
            </script>
        ");
    }else{
        $row = mysqli_fetch_array($result);
        $db_pass = $row["pass"];
        mysqli_close($con);

        //로그인 페이지에서 사용자가 입력한 패스워드와 
        //회원가입간 작성하여 DB에 저장된 패스워드의 일치 여부를 확인
        if($pass != $db_pass){
            echo("
                <script>
                    alert('입력한 패스워드가 다릅니다.\n확인 후 재입력 바랍니다.');
                    history.go(-1);
                </script>
            ");
            exit; //종료 명령
        }else{
            //세션 등록 : 항상 로그인된 상태라는 것을 확인
            session_start();
            $_SESSION["userid"] = $row["id"];
            $_SESSION["username"] = $row["name"];

            echo("
                <script>
                    location.href = './chat.php';
                </script>
            ");
        }
    }
?>