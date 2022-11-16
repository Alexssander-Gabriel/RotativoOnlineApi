<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "adiciona" && $param == '') {
    $dadosMetodoPagamento = json_decode(file_get_contents('php://input'), true);
    $sqlMetodoPagamentoInsert = "INSERT INTO Carteira (";
    if (is_array($dadosMetodoPagamento)){

        $contadorCamposMetodoPagamento = 1;

        foreach (array_keys($dadosMetodoPagamento) as $indice => $value) {
                if (count($dadosMetodoPagamento)-1 > $contadorCamposMetodoPagamento){
                    $sqlMetodoPagamentoInsert .= "{$value},";
                } else if ($contadorCamposMetodoPagamento <= 6){
                    $sqlMetodoPagamentoInsert .= "{$value}";
                }
            $contadorCamposMetodoPagamento ++;
        }

        $sqlMetodoPagamentoInsert .= ") VALUES (";

        $contadorValoresMetodoPagamento = 1;
        foreach (array_values($dadosMetodoPagamento) as $indice => $value) {
            if (count($dadosMetodoPagamento)-1 > $contadorValoresMetodoPagamento){
                $sqlMetodoPagamentoInsert .= "'{$value}',";
            } else {
                $sqlMetodoPagamentoInsert .= "{$value}";
            }
            $contadorValoresMetodoPagamento ++;
        }
    }
    $sqlMetodoPagamentoInsert .= ")";

    $db = DB::connect();
    $rs = $db->prepare($sqlMetodoPagamentoInsert);
    $exec = $rs->execute();

    if ($exec){
        echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }

} else if ($acao == "atualizar"){
    $dadosAtualizarMetodoPagamento = json_decode(file_get_contents('php://input'), true);
    $sqlAtualizarMetodoPagamento = "UPDATE CARTEIRA SET ";
    
    if (is_array($dadosAtualizarMetodoPagamento)){
        
        $CarteiraId = "";
        $Descricao = "";
        $NumeroCartao = "";
        $NomeCartao = "";
        $CodigoSegurancaoCartao = "";
        $TipoCartao = "";
        $CadastroId = "";

        if (isset($dadosAtualizarMetodoPagamento['CarteiraId'])){
            $CarteiraId =  $dadosAtualizarMetodoPagamento['CarteiraId'];
        }

        if (isset($dadosAtualizarMetodoPagamento['Descricao'])){
            $Descricao =  $dadosAtualizarMetodoPagamento['Descricao'];
        }

        if (isset($dadosAtualizarMetodoPagamento['NomeCartao'])){
            $NomeCartao =  $dadosAtualizarMetodoPagamento['NomeCartao'];
        }

        if (isset($dadosAtualizarMetodoPagamento['NumeroCartao'])){
            $NumeroCartao =  $dadosAtualizarMetodoPagamento['NumeroCartao'];
        }

        if (isset($dadosAtualizarMetodoPagamento['CodigoSegurancaoCartao'])){
            $CodigoSegurancaoCartao =  $dadosAtualizarMetodoPagamento['CodigoSegurancaoCartao'];
        }

        if (isset($dadosAtualizarMetodoPagamento['TipoCartao'])){
            $TipoCartao =  $dadosAtualizarMetodoPagamento['TipoCartao'];
        }

        if (isset($dadosAtualizarMetodoPagamento['CadastroId'])){
            $CadastroId =  $dadosAtualizarMetodoPagamento['CadastroId'];
        }

        $sqlAtualizarMetodoPagamento .= " Descricao = '" . $Descricao . "'";
        $sqlAtualizarMetodoPagamento .= " ,NumeroCartao = '" . $NumeroCartao . "'";
        $sqlAtualizarMetodoPagamento .= " ,NomeCartao = '" . $NomeCartao . "'";
        $sqlAtualizarMetodoPagamento .= " ,CodigoSegurancaoCartao = '" . $CodigoSegurancaoCartao . "'";
        $sqlAtualizarMetodoPagamento .= " ,TipoCartao = '" . $TipoCartao . "'";
        $sqlAtualizarMetodoPagamento .= " ,CadastroId = '" . $CadastroId . "'";

        $sqlAtualizarMetodoPagamento .= " WHERE CARTEIRAID = " . $CarteiraId;
    }
    
    $db = DB::connect();
    $rs = $db->prepare($sqlAtualizarMetodoPagamento);
    

    if ($rs->execute() === false){
        echo json_decode(["dados" => $rs->errorInfo()]);
    } else {
        echo json_encode(["dados" => 'Login atualizado com sucesso.']);
    }
}