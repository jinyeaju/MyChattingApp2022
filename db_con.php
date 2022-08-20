<?php
    /* 로컬 테스트용 */
    $con = mysqli_connect("localhost", "root", "000000", "chat", 3306);


    mysqli_query($con, "SET NAMES utf8"); //sql 언어의 설정(인코딩 utf8로 설정)
    
?>