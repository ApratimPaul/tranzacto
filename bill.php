<?php

    require "header.php";
    include "payment.php";

    if(!isset($_SESSION['uid'])){
        $uid = $_GET['uid'];
        $_SESSION['uid'] = $_GET['uid'];
        
    }else {
        if(isset($_GET['uid'])){
            $uid = $_GET['uid'];
            $_SESSION['uid'] = $_GET['uid'];
            
        }else{
            $uid = $_SESSION['uid']; 
        }
    }

    $employees = $database->getReference('users/'.$uid.'/employees/')->getSnapshot()->getValue();

    $details = $database->getReference('users/'.$uid.'/')->getSnapshot()->getValue();

    if(!isset($_SESSION['billno'])){
        $billno = $_GET['bill'];
        $_SESSION['billno'] = $_GET['bill'];
        
    }else {
        if(isset($_GET['bill'])){
            $billno = $_GET['bill'];
            $_SESSION['billno'] = $_GET['bill'];
            
        }else{
            $billno = $_SESSION['billno']; 
        }
    }
    

    $billdetails = $database->getReference('users/'.$uid.'/bills/'.$billno.'/')->getSnapshot()->getValue();

    if($billdetails['status'] == 'Paid'){
        header("Location: done.php");
    }

    if(isset($_GET['Tip']) && $_GET['newtip'] == "true"){
        $_SESSION['tipamount'] = $_GET['Tip'];
        $_SESSION['employeeID'] = $_GET['attendee_name'];
    }

    if(isset($_GET['Reset']) && $_GET['Reset'] == "true"){
        $uid = $_SESSION['uid'];
        $bill = $_SESSION['billno'];
        session_destroy();
        session_start();
        $_SESSION['uid'] = $uid;
        $_SESSION['billno'] = $bill;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>bill</title>
    <link rel="stylesheet" href="assets_billsummary/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&amp;display=swap">
    <link rel="stylesheet" href="assets_billsummary/css/Summary.css">
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
</head>

<body>
    <section class="card">
        <div class="col" id="header">
            <h2><?php print_r($details['organization']);?></h2>
            <p><?php print_r($details['address']);?><br></p>
            <p><strong>Phone number :</strong>&nbsp;<?php print_r($details['phonenumber']);?><br></p>
            <p><strong>GSTIN</strong>&nbsp;: 18AABCU9603R1ZM<br></p>
        </div>
        <hr>
        <h1>Bill Summary</h1>
        <section id="summary">
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr></tr>
                            </thead>
                            <tbody>

                                <?php

                                    $items = $billdetails['items'];

                                    $amount = 0;

                                    foreach($items as $item){
                                        $amount = $amount + ($item['amount'] * $item['quantity']);

                                        echo"
                                        
                                        <tr class='col-2'>
                                            <td><strong>". $item['itemname']."</strong></td>
                                            <td>". $item['amount'] * $item['quantity'] ."</td>
                                        </tr>
                                        ";

                                    }

                                    $tax = 0.18 * $amount;

                                    $amount = $amount + $tax;
                                    
                                    echo"
                                        
                                        <tr class='col-2'>
                                            <td><strong>Tax</strong></td>
                                            <td>". $tax ."</td>
                                        </tr>
                                        ";

                                
                                    if(isset($_SESSION['tipamount']) && $_SESSION['tipamount']>0){
                                        $amount = $amount + $_SESSION['tipamount'];

                                        echo"
                                        
                                        <tr class='col-2'>
                                            <td><strong>Tip</strong></td>
                                            <td>". $_SESSION['tipamount'] ."</td>
                                        </tr>
                                        ";
                                    }

                                ?>
                                <tr style="border-top: 2px solid black;">
                                    <td><strong>Total</strong></td>
                                    <td><?php print_r($amount);?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <?php

        if(!isset($_SESSION['tipamount']) || $_SESSION['tipamount']<0){
            


                print_r(
                    "<p class='text-start' id='help'>Your Smallest contribution is appreciated :&nbsp;</p>
                    <form action='bill.php' method='get' id='attendee_name'>
                        <select class='form-select' name='attendee_name' style='width:30%;' aria-label='Default select example'>
                            <option selected>Select Your Attendee</option>"
                );

                foreach ($employees as $employee){
                    print_r("<option value='".$employee['ID']."'> ".$employee['ID']." - ".$employee['name']."</option>");
                }
                        
                    
                        
                print_r("
                    </select>
                    <input type='number' name='Tip' placeholder='Tip Amount' style='width:50%;margin-left: 10px;border-color: #dbdbdb;'>
                    <button class='btn btn-primary' name ='newtip' value ='true' type='submit' style='margin-left: 10px;background: #2b6777;'>Give Tip</button>
                </form>"
                );
            
        }
        ?>

        <form action="bill.php" method="get">
        <div id="pay-way"><button class="btn btn-primary" name="Reset" value="true" type="submit" style="background: #2b6777;">Reset</button>        </div>  
        </form>

        <form class="form-horizontal" method="POST" action="<?php echo PAYPAL_URL; ?>">
                   <fieldset>
                       <input type='hidden' name='business' value='<?php echo PAYPAL_ID; ?>'>
                       <input type='hidden' name='item_name' value='Bill'>
                       <input type='hidden' name='item_number' value='123456789'>
                       <input type='hidden' name='amount' value='<?php print_r($amount/75); ?>'>
                       <input type='hidden' name='no_shipping' value='1'>
                       <input type='hidden' name='currency_code' value='<?php echo PAYPAL_CURRENCY; ?>'>
                       <input type='hidden' name='cancel_return' value='<?php echo PAYPAL_CANCEL_URL; ?>'>
                       <input type='hidden' name='return' value='<?php echo PAYPAL_RETURN_URL; ?>'>
                       <input type="hidden" name="cmd" value="_xclick">
                       <!-- Button -->
                       <div id="pay-way"><button class="btn btn-primary" type="submit" style="background: #2b6777;">Proceed to pay</button></div>
                   </fieldset>
        </form>
        
    </section>
    <script src="assets_billsummary/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>