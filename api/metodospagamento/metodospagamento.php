
<?php
if ($api == 'metodospagamento'){
    if ($method == "GET"){
        include_once("get.php");   
    } else if ($method == "POST") {
        include_once("post.php"); 
    } else if ($method == "PUT"){
        include_once("put.php");
    } else if ($method == "DELETE"){
        include_once("delete.php");
    }
} 
