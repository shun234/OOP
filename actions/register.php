<?php

include "../classes/User.php";


//create an object
$user = new User;


//call the method
$user->store($_POST);
//$_POST holds all the data from the in views/register.php
/*
    $_POST['first_name'];
    $_POST['last_name'];
    $_POST['username'];
    $_POST['password'];
*/




?>