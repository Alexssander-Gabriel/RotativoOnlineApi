<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * from diasatendimento da where da.EstacionamentoId = {$param} and da.Aberto = 'S' ORDER BY da.Dia ");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista"){

    $db = DB::connect();
    $rs = $db->prepare("SELECT * from diasatendimento da WHERE da.Aberto = 'S'  ORDER BY da.EstacionamentoId, da.Dia ");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    } 
} 