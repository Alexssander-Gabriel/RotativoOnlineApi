<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "atualiza" && $param !== '') {
    $dadosMetodoPagamento = json_decode(file_get_contents('php://input'), true);
    $sqlMetodoPagamentoUpdate = "UPDATE Carteira ";
    $sqlMetodoPagamentoUpdate .= "SET ";
    
    if (is_array($dadosMetodoPagamento)){

        $contadorCamposMetodoPagamento = 1;

        foreach (array_keys($dadosMetodoPagamento) as $indiceCampo => $valueCampo) {
            if ($valueCampo != 'CarteiraId'){

                foreach (array_values($dadosMetodoPagamento) as $indiceValor => $valueValor) {
                    if (count($dadosMetodoPagamento) > $contadorCamposMetodoPagamento){
                        $sqlMetodoPagamentoUpdate .= "{$valueCampo} = '{$valueValor}',";
                    } else {
                        $sqlMetodoPagamentoUpdate .= "{$valueCampo} = {$valueValor}";
                    }
                    $contadorCamposMetodoPagamento ++;
                }

            }
        }
    }

    $sqlMetodoPagamentoUpdate .= " WHERE CARTEIRAID = {$param} ";

    print_r($sqlMetodoPagamentoUpdate);

    // $db = DB::connect();
    // $rs = $db->prepare($sqlMetodoPagamentoUpdate);
    // $exec = $rs->execute();

    // if ($exec){
    //     echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
    // } else {
    //     echo json_encode(["dados" => 'Não existem dados para retornar']);
    // }

} 