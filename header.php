<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('./tranzacto-firebase-adminsdk-xda6e-b82928df18.json')
    ->withDatabaseUri('https://tranzacto-default-rtdb.firebaseio.com');

$auth = $factory->createAuth();
$database = $factory->createDatabase();

session_start();



// $email = "example2@gmail.com";
// $password = "123456312";


    // $signInResult = $auth->createUserWithEmailAndPassword($email, $password);
    // var_dump($signInResult);

    //$signInResult = $auth->signInWithEmailAndPassword($email, $password);

        
    // foreach ($signInResult->firebaseUserId() as $value) {
    //     print_r($value);
    //     print_r("<br/>");
    // }

?>