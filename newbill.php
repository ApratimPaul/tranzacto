<?php

    require "header.php";


    if(!isset($_SESSION['uid'])){
        header("Location: signin.php");
    }else{
        $uid = $_SESSION['uid'];
    }

    $details = $database->getReference('users/'.$uid.'/')->getSnapshot()->getValue();
    $timestamp = time();

    $billno = $timestamp;
    $date = date("d-m-Y",$timestamp);

    if(isset($_GET['Name'])){
        $_SESSION['name'] = $_GET['Name'];
    }

    if(!isset($_SESSION['items'])){
        $_SESSION['items'] = array();
    }

    if(isset($_GET['Item']) && isset($_GET['Quantity']) && isset($_GET['Price'])){
        if($_GET['Quantity'] != null || $_GET['Price'] != null || $_GET['Item'] != null){

            array_push(
                $_SESSION['items'],
                array(
                    "item" => $_GET['Item'],
                    "price" => $_GET['Price'],
                    "quantity" => $_GET['Quantity']
                )
                
            );
            
        }
    }

    if(isset($_GET['Reset']) && $_GET['Reset'] == "true"){
        $uid = $_SESSION['uid'];
        session_destroy();
        session_start();
        $_SESSION['uid'] = $uid;
    }

    if(isset($_GET['Generate']) && $_GET['Generate'] == "true"){
        if(isset($_SESSION['name']) && count($_SESSION['items'])> 0){

            $allitems = array();
            foreach($_SESSION['items'] as $item){
                $temp = strval(rand(111111111, 999999999));
                $allitems[$temp] =[
                    'itemname' => $item['item'],
                    'quantity' => $item['quantity'],
                    'amount' => $item['price'],
                ];
            }

            $database->getReference('users/'.$uid.'/bills/'.$billno.'/')
                ->set([
                    'billno' => $billno,
                    'timestamp' => $timestamp,
                    'date' => $date,
                    'customername' => $_SESSION['name'],
                    'items' => $allitems,
                    'status' => 'Not Paid',
                    ]);

            $uid = $_SESSION['uid'];
            session_destroy();
            session_start();
            $_SESSION['uid'] = $uid;

            header("Location: dashboard.php");

        }
    }



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>New bill</title>
    <link rel="stylesheet" href="assets_newbill/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="assets_newbill/css/Summary.css">
</head>

<body>
    <section id="main-section" type="submit">
        <div class="row">
            <div class="col" id="header">
                <h2><?php print_r($details['organization']);?></h2>
                <p><?php print_r($details['address']);?><br></p>
                <p><strong>Phone number :</strong>&nbsp;<?php print_r($details['phonenumber']);?><br></p>
                <p><strong>GSTIN</strong>&nbsp;: 18AABCU9603R1ZM<br></p>
            </div>
        </div>
        <hr>
        <h1>Bill Details</h1>
        <div class="row">
            <div class="col" id="bill">
                <p>Bill No. : (auto-generated)&nbsp;</p>
                <p>Date : <?php print_r($date); ?></p>
            </div>
        </div>

        
        <section id="summary">

        <?php
        
            if(isset($_SESSION['name'])){
                echo "<div class='Customer_name'>Customer Name : ". $_SESSION['name'] ."</div>";
            }else{
                echo "<form action='newbill.php' method='get'><input type='text' class='Customer_name' name='Name' placeholder='Name'><button class='btn btn-primary Customer_name' type='submit' style='margin-left: 25px;'>Save</button></form>";
            }
        
        ?>
            
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr></tr>
                            </thead>
                            <tbody>
                                <tr class="col-2">
                                    <td><strong>Item</strong></td>
                                    <td><strong>Quantity</strong></td>
                                    <td><strong>Price</strong></td>
                                    <td><strong>Amount</strong></td>
                                </tr>

                                <?php
                                    $amount = 0;
                                    if(isset($_SESSION['items'])){
                                        if(count($_SESSION['items'])>0){
                                            foreach($_SESSION['items'] as $item){
                                                $amount = $amount + ($item['quantity'] * $item['price']);
                                                echo"
                                                
                                                <tr>
                                                    <td>".$item['item']."</td>
                                                    <td>".$item['quantity']."</td>
                                                    <td>".$item['price']."</td>
                                                    <td>".($item['quantity'] * $item['price'])."</td>
                                                </tr>

                                                ";
                                            }
                                        }
                                    }

                                ?>
                                
                                <tr>
                                    <form action='newbill.php' method='get'>
                                        <td><input type="text" class="input_form" name="Item" placeholder="Item"></td>
                                        <td><input type="text" class="input_form" name="Quantity" placeholder="Quantity"></td>
                                        <td><input type="text" class="input_form" name="Price" placeholder="Price"></td>
                                        <td><button class="btn btn-primary" type="submit">Add</button></td>
                                    </form>
                                </tr>
                                <tr style="border-top: 2px solid black;">
                                    <td><br></td>
                                    <td><br></td>
                                    <td><strong>Total</strong></td>
                                    <td><?php print_r($amount); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <div id="generate_bill">
            <form style="margin-right: 10px;" action='newbill.php' method='get'>
                <td><input type="hidden" class="input_form" name="Reset" value = "true"></td>
                <td><button class="btn btn-primary" type="submit">Reset</button></td>
            </form>
            <form style="margin-right: 10px;" action='newbill.php' method='get'>
                <td><input type="hidden" class="input_form" name="Generate" value = "true"></td>
                <td><button class="btn btn-primary" type="submit">Generate Bill</button></td>
            </form>
        </div>
        
    </section>
    <script src="assets_newbill/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>