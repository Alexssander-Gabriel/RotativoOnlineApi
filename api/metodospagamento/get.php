<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("
        SELECT CA.*, CD.NOME AS NomeCadastro
        FROM CARTEIRA CA
        LEFT JOIN CADASTRO CD ON (CD.CADASTROID = CA.CADASTROID) 
        WHERE CA.CARTEIRAID = {$param}");
    $rs->execute();
    $obj = $rs->fetchObject(    );
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

}  else if ($acao == "listacadastro" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("
        SELECT CA.*, CD.NOME AS NomeCadastro
        FROM CARTEIRA CA
        LEFT JOIN CADASTRO CD ON (CD.CADASTROID = CA.CADASTROID) 
        WHERE CA.CADASTROID = {$param}");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista") {
    $sqlMetodosPagamento = "
    SELECT CA.*, CD.NOME AS NomeCadastro
    FROM CARTEIRA CA
    LEFT JOIN CADASTRO CD ON (CD.CADASTROID = CA.CADASTROID)";

    $db = DB::connect();
    $rs = $db->prepare($sqlMetodosPagamento);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
} else if ($acao == "utilizado" && $param != ''){
    $sqlMetodosPagamentoUtilizados = "
    SELECT CA.*, CD.NOME AS NomeCadastro
    FROM CARTEIRA CA
    INNER JOIN CADASTRO CD ON (CD.CADASTROID = CA.CADASTROID)
    INNER JOIN RECEBER RE on (RE.CARTEIRAID = CA.CARTEIRAID)
    WHERE CA.CADASTROID = {$param}";

    $db = DB::connect();
    $rs = $db->prepare($sqlMetodosPagamentoUtilizados);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);      
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    } 
} else if ($acao == "delete" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("DELETE FROM CARTEIRA WHERE CARTEIRAID = {$param}");

    if ($rs->execute() == false){
        echo json_encode(["dados" => $rs->errorInfo()]);
    } else {
        echo json_encode(["dados" => 'Método de pagamento excluido com sucesso.']);
    }

}   