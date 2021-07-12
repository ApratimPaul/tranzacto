<?php

    require "header.php";

    if(!isset($_SESSION['uid'])){
        header("Location: signin.php");
    }else{
        header("Location: dashboard.php");
    }

?>