<?php
if ($api == 'cadastro'){
    if ($method == "GET"){
        include_once("get.php");   
    } else if ($method == "POST") {
        include_once("post.php"); 
    }
}