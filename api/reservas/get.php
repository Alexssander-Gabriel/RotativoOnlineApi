<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM RESERVA WHERE  RESERVAID = {$param} ORDER BY DataEntrada, HoraEntrada, DataSaida, HoraSaida");
    $rs->execute();
    $obj = $rs->fetchObject(    );
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista") {
    $sqlReserva = "
    SELECT 
     RE.*, ES.NomeEstacionamento, CA.NOME AS NomeCadastro, FE.UrlFoto,
     10.00 AS ValorAntecipado
    FROM RESERVA RE
    LEFT JOIN ESTACIONAMENTO ES ON (ES.ESTACIONAMENTOID = RE.ESTACIONAMENTOID)  
    LEFT JOIN FOTOESTACIONAMENTO FE ON (FE.ESTACIONAMENTOID = ES.ESTACIONAMENTOID)
    LEFT JOIN CADASTRO CA ON (CA.CADASTROID = RE.CADASTROID)
    ORDER BY DataEntrada DESC, HoraEntrada DESC, DataSaida DESC, HoraSaida DESC";
    
    ///var_dump("Chegou aqui");

    $db = DB::connect();
    $rs = $db->prepare($sqlReserva);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
} else if ($acao == "reservacadastro" && $param != '') {
    $sqlReserva = "
    SELECT RE.*, ES.NomeEstacionamento, CA.NOME AS NomeCadastro, FE.UrlFoto
    FROM RESERVA RE
    LEFT JOIN ESTACIONAMENTO ES ON (ES.ESTACIONAMENTOID = RE.ESTACIONAMENTOID)  
    LEFT JOIN FOTOESTACIONAMENTO FE ON (FE.ESTACIONAMENTOID = ES.ESTACIONAMENTOID)
    LEFT JOIN CADASTRO CA ON (CA.CADASTROID = RE.CADASTROID)
    WHERE CA.CADASTROID = {$param}
    ORDER BY DataEntrada DESC, HoraEntrada DESC";
    
    ///var_dump("Chegou aqui");

    $db = DB::connect();
    $rs = $db->prepare($sqlReserva);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
} else if ($acao == "resevautilizada" && $param != ''){

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM RESERVA RE INNER JOIN fluxovaga FL ON (FL.ReservaId = RE.ReservaId) WHERE RE.RESERVAID = {$param} AND FL.Status = 'F'");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  

} else if ($acao == "cancelarreserva" && $param != ''){
    
    $db = DB::connect();
    $rs = $db->prepare("DELETE FROM RESERVA WHERE RESERVAID = {$param}");
    $rs->execute();
    //$obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["dados" => 'Reserva cancelada com sucesso']);

    //if ($obj){
    //    echo json_encode(["dados" => 'Reserva cancelada com sucesso']);       
    // } else {
    //    echo json_encode(["dados" => 'Erro ao cancelar reserva, tente novamente']);
    //}  
} else if ($acao == "visualizarResumo" && $param != ''){
    $sqlVisualizarResumo = "
        SELECT 
            Re.EstacionamentoId,
            ES.NomeEstacionamento, COALESCE(FP.Descricao,'') AS DescricaoFormaPagamento, 
            COALESCE(RR.Valor,0) as Valor, 
            RE.DataEntrada, RE.HoraEntrada, RE.DataSaida, RE.HoraSaida, 
            CASE when FL.Status = 'F' THEN 'Finalizado' else 'Em Aberto' END as STATUS,
            COALESCE(cc.Descricao,'') as DescricaoMetodoPagamento,
            Re.Observacao  
        FROM RESERVA RE 
        LEFT JOIN estacionamento ES ON (ES.EstacionamentoId = RE.EstacionamentoId) 
        LEFT JOIN receber RR ON (RR.ReservaId = RE.ReservaId) 
        LEFT JOIN formapagamento FP ON (FP.FormaPagamentoId = RR.FormaPagamentoId) 
        LEFT JOIN fluxovaga FL ON (FL.ReservaId = RE.ReservaId) 
        LEFT JOIN carteira cc on (cc.CarteiraId = rr.CarteiraId)
        WHERE RE.ReservaId = {$param}";

    //print_r($sqlVisualizarResumo);
    
    $db = DB::connect();
    $rs = $db->prepare($sqlVisualizarResumo);
    $rs->execute();
    $obj = $rs->fetchObject(    );

    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  
}
