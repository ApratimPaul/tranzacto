<?php

    require "header.php";

    session_destroy();

    header("Location: signin.php");

?>