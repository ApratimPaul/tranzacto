<?php

    require "header.php";

    if(!isset($_SESSION['uid'])){
        header("Location: signin.php");
    }else{
        $uid = $_SESSION['uid'];
    }

    $data = $database->getReference('users/'.$uid.'/bills/')
    // order the reference's children by the values in the field 'height' in ascending order

    ->getSnapshot()->getValue();

    $orgname = $database->getReference('users/'.$uid.'/')->getSnapshot()->getValue();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Table - Brand</title>
    <link rel="stylesheet" href="assets_customer/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets_customer/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets_customer/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets_customer/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets_customer/css/table.css">
</head>

<body id="page-top" style="background: #c8d8e4;">
    <div id="wrapper" style="background: #ffffff;">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: #2b6777;">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><img class="img-fluid" src="assets_customer/img/writing.png" style="width: 200px;margin-top: 0px;"></div>
                </a>
                <hr class="sidebar-divider my-0"><br/>
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php" style="position: relative;left: 10px;"><i class="fas fa-table"></i><span style="font-size: 19.6px;">Ledger</span></a></li><br/>
                    <li class="nav-item"><a class="nav-link active" href="table_employee.php" style="position: relative;left: 10px;bottom: 25px;"><i class="fas fa-table"></i><span style="font-size: 19.6px;">Employee Info</span></a></li>
                </ul>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content" style="background: #c8d8e4;height: 40vh;">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top" style="/*background-color: #2b6777!important;*/">
                    <div class="container-fluid">
                        <p style="padding: 0;margin: 0;font-size: 32px;font-weight: 700;">Ledger</p>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                            <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small" style="font-size: 20px;"><?php print_r($orgname['organization']); ?></span><img class="border rounded-circle img-profile" src="logo.png"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <div class="dropdown-divider"></div><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid" style="padding: 5% 2% 5% 2%;">
                    <div class="card shadow">
                        <div class="card-header py-3" style="display: flex;justify-content: space-between;">
                            <p class="text-primary m-0 fw-bold">Customer Info</p>
                            <a href="newbill.php" style="text-decoration: none;">
                                <p style="position: relative;/*right: 58%;*/">Add New</p>
                            </a>
                        </div>
                        <div class="card-body">
                           
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Bill No.</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th class="receipt-centre">Reciept</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        if(isset($data) && count($data)>0){
                                            foreach(array_reverse($data) as $value){

                                                $items = $value['items'];

                                                $amount = 0;

                                                foreach($items as $item){
                                                    $amount = $amount + ($item['amount'] * $item['quantity']);
                                                }

                                                echo "
                                                
                                                <tr>
                                                <td>".$value['customername']."</td>
                                                <td>".$value['billno']."</td>
                                                <td>".$value['date']."</td>
                                                <td>₹".$amount."</td>
                                                <td>".$value['status']."</td>
                                                <td><a class='receipt-centre' href='reciept.php?bill=".$value['billno']."' style='font-size: 20px;'><i class='fa fa-print'></i></a></td>
                                                </tr>
                                                
                                                ";
                                            }
                                        }else{
                                            echo "
                                                
                                                <tr>
                                                <td></td>
                                                <td></td>
                                                <td>No records</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                </tr>
                                                
                                            ";
                                        }
                                        
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr></tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets_customer/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets_customer/js/theme.js"></script>
</body>

</html>