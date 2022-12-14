<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

if (isset($_GET['path'])) {
    $path = explode("/", $_GET['path']);
} else {
    echo "Caminho não existe"; exit;
}

if (isset($path[1])) {
 $api = $path[1];
} else {
    echo "Caminho não existe";
}


if (isset($path[1])) { 
    $api = $path[1];
} else {
    echo "Caminho não existe"; exit;
} 

if (isset($path[2])) { 
    $acao = $path[2];
} else {
    $acao = '';
} 

if (isset($path[3])) { 
    $param = $path[3];
} else {
    $param = '';
} 

$method = $_SERVER['REQUEST_METHOD'];
include_once("classes/db.class.php");
include_once("api/login/login.php");
include_once("api/cidade/cidade.php");
include_once("api/estacionamentos/estacionamentos.php");
include_once("api/cadastro/cadastro.php");
include_once("api/reservas/reservas.php");
include_once("api/metodospagamento/metodospagamento.php");
include_once("api/reservar/reservar.php");
include_once("api/formapagamento/formapagamento.php");
include_once("api/diasatendimento/diasatendimento.php");

