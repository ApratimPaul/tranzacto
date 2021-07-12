<?php

    require "header.php";

    

    use Spipu\Html2Pdf\Html2Pdf;

    if(isset($_GET['bill'])){

        if(!isset($_SESSION['uid'])){
            header("Location: signin.php");
        }else{
            $uid = $_SESSION['uid'];
        }
        
        $billno = $_GET['bill'];

        $details = $database->getReference('users/'.$uid.'/')->getSnapshot()->getValue();
        $billdetails = $database->getReference('users/'.$uid.'/bills/'.$billno.'/')->getSnapshot()->getValue();
        $url = urlencode("https://tranzacto.000webhostapp.com/bill.php?bill=".strval($billno)."&uid=".$uid );

        $items = $billdetails['items'];

        $amount = 0;

        $itemhtml = null;

        foreach($items as $item){
            $amount = $amount + ($item['amount'] * $item['quantity']);

            $itemhtml = $itemhtml . "
            <tr style='text-align: center;'>
                <td>". $item['itemname']."</td>
                <td>". $item['quantity'] ."</td>
                <td>". $item['amount'] ."</td>
                <td>". ($item['amount'] * $item['quantity']) ."</td>
            </tr>
            ";

        }

        $total = $amount + 0.18 * $amount;




        ob_start();
        $html2pdf = new Html2Pdf();
        

        $html2pdf->writeHTML('
        <div style="margin: 0; padding: 0;">
        <div class="section" style="margin-top: 10px">
        <div class="row">
            <div
            class="coloumn"
            style="
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            "
            >
            <h2 style="padding: 0 0 10px 0; margin: 0; font-size: 36px">
                '. $details['organization'].'
            </h2>
            <p style="padding: 0; margin-top: 5px; font-size: 16px; text-align: left;">
                '. $details['address'].'
            </p>
            <br/>
            <p style="padding: 0; margin: 0; font-size: 16px">
                <strong>Phone number : </strong>'. $details['phonenumber'].'
            </p>
            <p style="padding: 0; margin: 0; font-size: 16px">
                <strong>GSTIN :</strong> 18AABCU9603R1ZM
            </p>
            </div>
        </div>
        </div>

        <hr / style="border-top:3px solid gray; margin-top: 20px; width: 60%; ">

        <div class="row">
        <div
            class="column"
            style="
            display: flex;
            justify-content: space-between;
            
            border-bottom: 2px dotted black;
            "
        >
            <p style="padding: 0 0 0 0px; margin: 0 0 10px 0; font-size: 18px">
            BILL NO. : '. $billno .'
            </p>
            <p style="padding: 0 30px 0 0; margin: 0 0 10px 0; font-size: 18px">
            Date : '.$billdetails['date'].'
            </p>
        </div>
        </div>
        <table style="width: 80%; margin: 30px auto 0 auto ;">
        <tr>
            <th><pre>       Items       </pre></th>
            <th><pre>     Quantity    </pre></th>
            <th><pre>     Price     </pre></th>
            <th><pre>     Amount     </pre></th>
        </tr>
        
        <tr style="text-align: center">
            <td>
            <pre style="font-size: 5px;">
            </pre>
            </td>
        </tr>

        '.$itemhtml.'


        <tr style="text-align: center">
            <td>
            <pre style="font-size: 2px;">
            </pre>
            </td>
        </tr>

        <tr style="text-align: center;">
            <td style="border-top: 1px solid black; padding-top: 15px;"></td>
            <td style="border-top: 1px solid black; padding-top: 15px;"></td>
            <td style="border-top: 1px solid black; padding-top: 15px;">Subtotal</td>
            <td style="border-top: 1px solid black; padding-top: 15px;">'. $amount .'</td>
        </tr>

        <tr style="text-align: center">
            <td>
            <pre style="font-size: 2px;">
            </pre>
            </td>
        </tr>

        <tr style="text-align: center;">
            <td></td>
            <td></td>
            <td>Tax</td>
            <td>'. 0.18 * $amount.'</td>
        </tr>


        
        
        
        <tr style="text-align: center">
            <td>
            <pre style="font-size: 2px;">
            </pre>
            </td>
        </tr>

        <tr style="text-align: center;">
            <td style="border-top: 1px solid black; padding-top: 15px;"></td>
            <td style="border-top: 1px solid black; padding-top: 15px;"></td>
            <th style="border-top: 1px solid black; padding-top: 15px;">Total</th>
            <th style="border-top: 1px solid black; padding-top: 15px;">'. $total .'</th>
        </tr>

        </table>
        <div>
        <br/>
        <p>Scan the QR Code to Pay :  </p>
        <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$url.'&choe=UTF-8" title="Link to Google.com" />
        </div>
    </div>
        ');

        ob_end_clean();
        $html2pdf->output($billno.'.pdf');

    }

?>