<?php

if ($acao == 'delete'){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "delete" && $param !== '') {

    $db = DB::connect();
    $rs = $db->prepare("DELETE FROM CARTEIRA WHERE CARTEIRAID = {$param}");
    $exec = $rs->execute();

    if ($exec){
        echo json_encode(["dados" => 'Dados foram deletados com sucesso!']);
    } else {
        echo json_encode(["dados" => 'Erro ao Deletar registro']);
    }

} 