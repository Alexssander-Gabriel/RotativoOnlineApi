<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM CIDADE  WHERE CIDADEID = {$param} ORDER BY NOMECIDADE");
    $rs->execute();
    $obj = $rs->fetchObject(    );
            
    if ($obj){
        echo json_encode(["dados" => $obj]);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista") {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM CIDADE ORDER BY NOMECIDADE");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode(["dados" => $obj]);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
}   