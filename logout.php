<?php
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ablogin.php');
    exit();
}
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: admin.php');
    exit();
}
?>

<style>
    input[type=text], select {
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        background-color: #000;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }




</style>
