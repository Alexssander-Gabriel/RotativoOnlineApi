<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "verificacalculavalor") {

    $dadosVerificaCalculaValor = json_decode(file_get_contents('php://input'), true);


    $sqlVerificaCalculaValor = "SELECT f_verificaCalculaValor(";

    if (is_array($dadosVerificaCalculaValor)){
        $idEstacionamento;
        $DataEntrada;
        $HoraEntrada;
        $DataSaida;
        $HoraSaida;

        if (isset($dadosVerificaCalculaValor['Estacionamento']['EstacionamentoId'])){
            $idEstacionamento =  $dadosVerificaCalculaValor['Estacionamento']['EstacionamentoId'];
        }

        if (isset($dadosVerificaCalculaValor['DataEntrada'])){
            $DataEntrada =  substr($dadosVerificaCalculaValor['DataEntrada'], 0,10);
            $HoraEntrada =  substr($dadosVerificaCalculaValor['DataEntrada'], 11,8);
        }

        if (isset($dadosVerificaCalculaValor['DataSaida'])){
            $DataSaida =  substr($dadosVerificaCalculaValor['DataSaida'], 0,10);
            $HoraSaida =  substr($dadosVerificaCalculaValor['DataSaida'], 11,8);
        }    
        
        $sqlVerificaCalculaValor .= "{$idEstacionamento},'{$DataEntrada} {$HoraEntrada}','{$DataSaida} {$HoraSaida}'";
    }

    $sqlVerificaCalculaValor .= ") as Verificacao";

    $db = DB::connect();
    $rs = $db->prepare($sqlVerificaCalculaValor);
    $rs->execute();
    $obj = $rs->fetchObject();
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    } 
} else if ($acao == "confirmarreserva"){
        $dadosConfirmarReseva = json_decode(file_get_contents('php://input'), true);


        $sqlConfirmaReserva = "INSERT INTO RESERVA(CadastroId, DataEntrada, DataSaida, EstacionamentoId, HoraEntrada, HoraSaida, Observacao)";

        $sqlPagamentoAntecipado = "INSERT INTO RECEBER (CadastroId, CarteiraId, FormaPagamentoId, Status, Valor, ReservaId) ";
        
        $PagamentoAntecipado = 'N';
        $paramCadastroId = 0;
        $ReservaId = 0;
        
        if (is_array($dadosConfirmarReseva)){
            $idCadastro = "";
            $idEstacionamento = "";
            $DataEntrada = "";
            $HoraEntrada = "";
            $DataSaida = "";
            $HoraSaida = "";
            $Observacao = "";
            $MetodosPagamentoId = "";
            $FormaPagamentoId = "";
            $Valor = "";

    
            if (isset($dadosConfirmarReseva['CadastroId'])){
                $idCadastro =  $dadosConfirmarReseva['CadastroId'];
                $paramCadastroId = $dadosConfirmarReseva['CadastroId'];
            }

            if (isset($dadosConfirmarReseva['Estacionamento']['EstacionamentoId'])){
                $idEstacionamento =  $dadosConfirmarReseva['Estacionamento']['EstacionamentoId'];
            }
    
            if (isset($dadosConfirmarReseva['DataEntrada'])){
                $DataEntrada =  substr($dadosConfirmarReseva['DataEntrada'], 0,10);
                $HoraEntrada =  substr($dadosConfirmarReseva['DataEntrada'], 11,8);
            }
    
            if (isset($dadosConfirmarReseva['DataSaida'])){
                $DataSaida =  substr($dadosConfirmarReseva['DataSaida'], 0,10);
                $HoraSaida =  substr($dadosConfirmarReseva['DataSaida'], 11,8);
            }    

            if (isset($dadosConfirmarReseva['Observacao'])){
                $Observacao =  $dadosConfirmarReseva['Observacao'];
            }

            if (isset($dadosConfirmarReseva['MetodosPagamento']['CarteiraId'])){
                $MetodosPagamentoId =  $dadosConfirmarReseva['MetodosPagamento']['CarteiraId'];
            }

            if (isset($dadosConfirmarReseva['FormaPagamento']['FormaPagamentoId'])){
                $FormaPagamentoId =  $dadosConfirmarReseva['FormaPagamento']['FormaPagamentoId'];
            }

            if (isset($dadosConfirmarReseva['Valor'])){
                $Valor =  $dadosConfirmarReseva['Valor'];
            }

            if (isset($dadosConfirmarReseva['PagamentoAntecipado'])){
                $PagamentoAntecipado =  $dadosConfirmarReseva['PagamentoAntecipado'];
            }
            
            $sqlConfirmaReserva .= " Values ({$idCadastro}, '{$DataEntrada}', '{$DataSaida}', {$idEstacionamento}, '{$HoraEntrada}', '{$HoraSaida}', '{$Observacao}')";
            
            $sqlPagamentoAntecipado .= " VALUES ({$idCadastro},{$MetodosPagamentoId},'A',{$Valor},";
        }

    
        //print_r($sqlConfirmaReserva);
        

        //$exec = $rsP->execute();
                
        // if ($obj){
        //     echo json_encode('{
        //         "Dados":{
        //            "Retorno":"Reserva confirmada com sucesso.",
        //            "Sucesso":"True"
        //         }
        //        }');    
        // } else {
        //     echo json_encode('{
        //         "Dados":{
        //            "Retorno":"Erro ao confirmar Reserva, tente novamente.",
        //            "Sucesso":"False"
        //         }
        //        }');
        // } 

        if ($PagamentoAntecipado == 'S'){
            $db = DB::connect();
            $rs = $db->prepare("SELECT * FROM RESERVA WHERE  CADASTROID = {$paramCadastroId} ORDER BY DataEntrada DESC");
            $rs->execute();
            $obj = $rs->fetchObject(    );

            if ($obj){
                $sqlPagamentoAntecipado .= "{$obj->ReservaId}";

                $db = DB::connect();
                $rs = $db->prepare($sqlPagamentoAntecipado);
                $rs->execute();

                if ($rs->execute() == false){
                    echo json_encode(["dados" => $rsp->errorInfo()]);
                } else {
                    echo json_encode(["dados" => 'Reserva confirmada com sucesso.']);
                }
            }
            
        } else {

            $db = DB::connect();
            $rs = $db->prepare($sqlConfirmaReserva);

            if ($rs->execute() == false){
                echo json_encode(["dados" => $rsp->errorInfo()]);
            } else {
                echo json_encode(["dados" => 'Reserva confirmada com sucesso.']);
            }
        }
        
        // else {
        //     if ($exec){
        //         echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
        //     } else {
        //         echo json_encode(["dados" => 'Não existem dados para retornar']);
        //     }
        // }
}