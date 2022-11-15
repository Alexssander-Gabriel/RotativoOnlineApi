<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "reservar") {

    $dadosReserva = json_decode(file_get_contents('php://input'), true);
    $sqlReservaInsert = "INSERT INTO Carteira (";
    if (is_array($dadosReserva)){

        $contadorCamposMetodoPagamento = 1;

        foreach (array_keys($dadosReserva) as $indice => $value) {
                if (count($dadosReserva) > $contadorCamposMetodoPagamento){
                    $sqlReservaInsert .= "{$value},";
                } else {
                    $sqlReservaInsert .= "{$value}";
                }
            $contadorCamposMetodoPagamento ++;
        }

        $sqlMetodoPagamentoInsert .= ") VALUES (";

        $contadorValoresMetodoPagamento = 1;
        foreach (array_values($dadosReserva) as $indice => $value) {
            if (count($dadosReserva) > $contadorValoresMetodoPagamento){
                $sqlReservaInsert .= "'{$value}',";
            } else {
                $sqlReservaInsert .= "{$value}";
            }
            $contadorValoresMetodoPagamento ++;
        }
    }
    $sqlReservaInsert .= ")";

    print_r(sqlReservaInsert);
        
    // $db = DB::connect();
    // $rs = $db->prepare(sqlReservaInsert);
    // $rs->execute();

    // if ($exec){
    //     echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
    // } else {
    //     echo json_encode(["dados" => 'Não existem dados para retornar']);
    // } 

}
