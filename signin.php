<?php

    require "header.php";

    $error = "";

    if(isset($_GET["email"]) && isset($_GET["password"])){
        $email = $_GET["email"];
        $password = $_GET["password"];
        
        if($password !== null){
            $signInResult = $auth->signInWithEmailAndPassword($email, $password);

            if($signInResult->firebaseUserId() !== null){
                $_SESSION['uid'] = $signInResult->firebaseUserId();
                header("Location: dashboard.php");
                $error = "Login Success !";
            }else{
                $error = "Login failed !";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>tranzacto-login</title>
    <link rel="stylesheet" href="assets-sign in/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets-sign in/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets-sign in/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets-sign in/css/styles.css">
</head>

<body>
    <section id="login-section" style="border-style: none;border-right-style: none;border-right-color: rgb(255, 0, 0);">
        <div class="row" id="main-row">
            <div class="col" style="border-right: 7px none var(--bs-pink) ;"><img class="img-fluid" id="logo" src="assets-sign in/img/logo%20(1)-main.png" style="border-right-style: solid;"></div>
            <div class="col" id="login-form" style="color: #212529;">

            <form>
                <section id="login" class="login-dark" style="border-right-color: #2b6777;">
                    <form action="signin.php" method="get">
                        <h2 class="visually-hidden">Login Form</h2>
                        <div class="illustration"><i class="fas fa-cash-register" style="color: #2b6777;width: 100px;"></i></div>
                        <div class="mb-3" ><p style="color:red;"><?php echo $error; ?></p></div>
                        <div class="mb-3" ><input class="form-control" type="email" name="email" placeholder="Email"></div>
                        <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password"></div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100" type="submit">Log in</button></div><a class="forgot" href="signup.php">New here? Create an account!</a>
                    </form>
                </section>
            </form>
            </div>
        </div><a id="insta" href="https://www.instagram.com/tranzacto_/" target="_blank">
            <p>Contact us</p><img class="img-fluid" id="link-img" src="assets-sign in/img/instagram-logo-png-2426.png">
        </a>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>