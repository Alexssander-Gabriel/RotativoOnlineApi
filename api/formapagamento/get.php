<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM FORMAPAGAMENTO WHERE  FORMAPAGAMENTOID = {$param} ORDER BY DESCRICAO");
    $rs->execute();
    $obj = $rs->fetchObject(    );
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista") {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM FORMAPAGAMENTO WHERE FORMAPAGAMENTOID = 1 OR FORMAPAGAMENTOID = 5");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
}
