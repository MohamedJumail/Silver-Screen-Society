<?php
require 'constants.php';

$connection = new mysqli(DB_Host,DB_User,DB_Pass,DB_Name);
if(mysqli_errno($connection)){
    die(mysqli_error($connection));
}