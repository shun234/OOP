<?php

include "../classes/User.php";


//create an object
$user = new User;

//call the method
$user->login($_POST);
//$_POST holds all the data from the form or from the  views>login.php or from the index.php or the login form
    // username and password

    /*
        $_POST['username'];
        $_POST['password'];
        

    */




?>