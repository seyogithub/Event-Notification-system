<?php

$servername = "localhost"; 
$username = "root";       
$password = "";            
$dbname = "event_notification"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) 
   
    { 
        ?>
    <script>
        alert("Connection failed: " . $conn->connect_error);
    </script>
    <?php
    }

