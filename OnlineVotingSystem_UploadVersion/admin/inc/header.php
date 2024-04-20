
<?php
    session_start();
    require_once("config.php");
    if($_SESSION['key']!="AdminKey"){
        echo "<script>location.assign('logout.php');</script>";
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel-Online Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row bg-darkblue text-black">
            <div class="col-1">
                <img src="../assets/images/logo.png" class="text-cwenter"width="80px"/>
            </div>
            <div class="col-11 my-auto" >
                <h3>ONLINE VOTING SYSTEM -  <small>Welcome <?php echo $_SESSION['username'];?></small></h3>
            </div>
        </div>
    

    
