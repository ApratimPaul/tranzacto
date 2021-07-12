<?php

    require "header.php";

    $error = null;

    if(isset($_GET["email"]) ){
        
        $email = $_GET["email"];
        $name = $_GET["name"];
        $organization = $_GET["organizationname"];
        $phonenumber = $_GET["phonenumber"];
        $password = $_GET["password"];
        $confirmpassword = $_GET["confirmpassword"];
        $address = $_GET["address"];

        if($password != $confirmpassword){
            $error = "Passwords do not match";
        }

        if(strlen($password)<6 || strlen($password)>32){
            $error = "Passwords should be between 6 and 32 charecters";
        }

        if($email == null || $name == null || $organization == null || $phonenumber == null || $password == null){
            $error = "Fill all required fields";
        }
        
        if($error == null){
            $createUserResult =  $auth->createUserWithEmailAndPassword($email, $password);
            $signInResult = $auth->signInWithEmailAndPassword($email, $password);
            if($signInResult->firebaseUserId() !== null){
                $_SESSION['uid'] = $signInResult->firebaseUserId();
                $database->getReference('users/'.$signInResult->firebaseUserId().'/')
                    ->set([
                        'name' => $name,
                        'organization' => $organization,
                        'phonenumber' => $phonenumber,
                        'email' => $email,
                        'address' => $address,
                        ]);
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
    <link rel="stylesheet" href="assets-sign up/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets-sign up/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets-sign up/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets-sign up/css/styles.css">
</head>

<body>
    <section id="login-section" style="border-style: none;border-right-style: none;border-right-color: rgb(255, 0, 0);">
        <div class="row" id="main-row">
            <div class="col" style="border-right: 7px none var(--bs-pink) ;"><img class="img-fluid" id="logo" src="assets-sign up/img/logo%20(1)-main.png" style="border-right-style: solid;"></div>
            <div class="col" id="login-form" style="color: #212529;">
                <form>
                    <section id="login" class="login-dark" style="border-right-color: #2b6777;">
                        <form method="post">
                            <h2 class="visually-hidden">Login Form</h2>
                            <div class="illustration"><i class="fas fa-cash-register" style="color: #2b6777;width: 100px;"></i></div>

                            <div class="mb-3" ><p style="color:red;"><?php echo $error; ?></p></div>
                            <div class="mb-3">
                                <div id="name-input" style="margin-bottom: 10px;">
                                    <input class="form-control" type="Name" name="name" placeholder="Full Name">
                                    <input class="form-control" type="Name" name="organizationname" placeholder="Organization Name" style="margin-left: 10px;">
                                </div>

                                <input class="form-control" type="text" name="address" placeholder="Address" style="margin-bottom: 10px;">
                                <input class="form-control" type="email" name="email" placeholder="Email" style="margin-bottom: 10px;">
                                <input class="form-control" type="tel" name="phonenumber" placeholder="Mobile Number" style="margin-bottom: 10px;">

                                <div id="password-input">
                                    <input class="form-control" type="password" name="password" placeholder="Password">
                                    <input class="form-control" type="password" name="confirmpassword" placeholder="Confirm Password" style="margin-left: 10px;">
                                </div>
                            </div>
                            <div class="mb-3"><button class="btn btn-primary d-block w-100" type="submit">Sign Up</button></div>
                            <a class="forgot" href="signin.php">Already have an account ?&nbsp;</a>
                        </form>
                    </section>
                </form>
            </div>
        </div><a id="insta" href="https://www.instagram.com/tranzacto_/" target="_blank">
            <p>Contact us</p><img class="img-fluid" id="link-img" src="assets-sign up/img/instagram-logo-png-2426.png">
        </a>
    </section>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>