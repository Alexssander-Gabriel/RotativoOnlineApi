<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "estacionamentodisponivelreserva") {

    $dadosVerificaEstacionamentoDisponivel = json_decode(file_get_contents('php://input'), true);

        $sqlEstacionamento = "
        Select 
            E.EstacionamentoId, E.NomeEstacionamento, E.BairroEndereco, E.Complemento, E.Email, E.Endereco, E.NumeroCep, E.NumeroEndereco, E.NumeroTelefone1, E.NumeroTelefone2, E.NumeroVagas, E.PrecoHora, E.PrecoLivre, E.Sobre,
            c.NomeCidade, c.Estado, fe.UrlFoto, e.linkmaps as LinkMaps, E.ChavePix, 
            CASE 
             WHEN E.TipoChavePix = 1 THEN 'CNPJ'
             WHEN E.TipoChavePix = 2 THEN 'CPF'
             WHEN E.TipoChavePix = 3 THEN 'E-mail'
             WHEN E.TipoChavePix = 4 THEN 'Telefone'
             WHEN E.TipoChavePix = 5 THEN 'Aleatória'
            ELSE 
             'Chave Pix Não Cadastrada' 
            END 
             as TipoChavePix,
             (SELECT f_vagasLocacao(e.EstacionamentoId, Now(),'D')) > 0 as VagasReserva
        from Estacionamento E
        left join cidade c on (c.cidadeid = e.cidadeid)
        left join empresa em on (em.empresaid = e.empresaid)
        left join fotoestacionamento fe on (fe.estacionamentoid = e.estacionamentoid and fe.EstacionamentoId)
        where 1 = 1
        and (SELECT f_SituacaoEmpresa(e.EmpresaId,1) < 3
        and (SELECT f_vagasLocacao(e.EstacionamentoId, Now(),'D')) > 0)";

        "and EXISTS (
            SELECT *
            FROM diasatendimento da
            where da.EstacionamentoId = e.EstacionamentoId
            and da.Dia = WEEKDAY(NOW())
            AND da.HoraEntrada <= DATE_FORMAT(NOW(), '%H:%i:%s')  AND da.HoraSaida > DATE_FORMAT(NOW(), '%H:%i:%s')
            and da.Aberto = 'S'
        )
        ORDER BY E.NomeEstacionamento";

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
}