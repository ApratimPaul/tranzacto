<?php

    require "header.php";

    if(!isset($_SESSION['uid'])){
        header("Location: signin.php");
    }else{
        $uid = $_SESSION['uid'];
    }

    $empid = rand(111111,999999);

    if(isset($_GET['Reset']) && $_GET['Reset'] == "true"){
        $uid = $_SESSION['uid'];
        session_destroy();
        session_start();
        $_SESSION['uid'] = $uid;
    }

    if(isset($_GET['Generate']) && $_GET['Generate'] == "true"){
        if(isset($_GET['employeename']) && $_GET['phonenumber']){
            $database->getReference('users/'.$uid.'/employees/'.$empid.'/')
                ->set([
                    'ID' => $empid,
                    'name' => $_GET['employeename'],
                    'phonenumber' => $_GET['phonenumber'],
                    ]);

            $uid = $_SESSION['uid'];
            session_destroy();
            session_start();
            $_SESSION['uid'] = $uid;

            header("Location: table_employee.php");

        }
    }



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>new employee</title>
    <link rel="stylesheet" href="assets_employeedetails/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="assets_employeedetails/css/Summary.css">
</head>

<body>
    <section id="main-section" type="submit">
        <h1 class="text-center" style="margin-bottom: 20px;">Employee Details</h1>
        <form action="employee_details.php" method="get">
            <div class="employee" style="margin-bottom: 30px;">
                <input class="form-control" type="text" name="employeename" placeholder="Employee Name">
                <input class="form-control" type="tel" style="margin-left: 25px;" name="phonenumber" placeholder="Phone Number">
            </div>
            <div class="employee">
                <button class="btn btn-primary employee" name="Reset" value="true" type="submit" style="position: relative;left: 50px;">Reset</button>
                <button class="btn btn-primary" name="Generate" type="submit" value="true" style="position: relative;right: 50px;">Save</button>
            </div>
        </form>
    </section>
    <script src="assets_employeedetails/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>