<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "pcbuilder";      


$conn = new mysqli($servername, $username, $password, $dbname);

if(!$conn){echo "DATABASE DIDN'T CONNCECT";}
    
?>