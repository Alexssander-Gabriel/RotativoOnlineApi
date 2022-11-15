<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "lista" && $param != '') {

    $db = DB::connect();
    $rs = $db->prepare("
    Select 
        E.EstacionamentoId, E.NomeEstacionamento, E.BairroEndereco, E.Complemento, E.Email, E.Endereco, E.NumeroCep, E.NumeroEndereco, E.NumeroTelefone1, E.NumeroTelefone2, E.NumeroVagas, E.PrecoHora, E.PrecoLivre, E.Sobre,
        c.NomeCidade, c.Estado, GROUP_CONCAT(fe.UrlFoto) as UrlFoto, e.linkmaps as LinkMaps, E.ChavePix, 
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
    left join fotoestacionamento fe on (fe.estacionamentoid = e.estacionamentoid)
    WHERE E.ESTACIONAMENTOID = {$param}");
    $rs->execute();
    $obj = $rs->fetchObject(    );
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }   

} else if ($acao == "lista") {
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

    ORDER BY E.NomeEstacionamento";
    //     --and (SELECT f_SituacaoEmpresa(e.EmpresaId,1) < 3)
    ///var_dump("Chegou aqui");

    $db = DB::connect();
    $rs = $db->prepare($sqlEstacionamento);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }  

} else if ($acao == "filtroPreciso"){
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
    /*and (SELECT f_SituacaoEmpresa(e.EmpresaId,1) < 3*/
    and (SELECT f_vagasLocacao(e.EstacionamentoId, Now(),'D')) > 0
    and EXISTS (
        SELECT *
        FROM diasatendimento da
        where da.EstacionamentoId = e.EstacionamentoId
        and da.Dia = WEEKDAY(NOW())
        AND da.HoraEntrada <= DATE_FORMAT(NOW(), '%H:%i:%s')  AND da.HoraSaida > DATE_FORMAT(NOW(), '%H:%i:%s')
        and da.Aberto = 'S'
    )
    ORDER BY E.NomeEstacionamento";
    
    $db = DB::connect();
    $rs = $db->prepare($sqlEstacionamento);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode($obj);       
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    } 

} else if ($acao == "estacionamentofotos" && $param != ''){

    $db = DB::connect();
    $rs = $db->prepare("SELECT * FROM fotoestacionamento WHERE ESTACIONAMENTOID = {$param}");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
            
    if ($obj){
        echo json_encode(["sucesso" => 'true', "dados" => $obj]);       
    } else {
        echo json_encode(["sucesso" => 'false', "dados" => '']);
    } 
}  


//          (SELECT f_vagasReserva(e.EstacionamentoId, Now(),Now(),'D')) as VagasReserva




// Select 
// E.EstacionamentoId, E.NomeEstacionamento, E.BairroEndereco, E.Complemento, E.Email, E.Endereco, E.NumeroCep, E.NumeroEndereco, E.NumeroTelefone1, E.NumeroTelefone2, E.NumeroVagas, E.PrecoHora, E.PrecoLivre, E.Sobre,
// c.NomeCidade, c.Estado, fe.UrlFoto, e.linkmaps as LinkMaps, E.ChavePix, 
// CASE 
//  WHEN E.TipoChavePix = 1 THEN 'CNPJ'
//  WHEN E.TipoChavePix = 2 THEN 'CPF'
//  WHEN E.TipoChavePix = 3 THEN 'E-mail'
//  WHEN E.TipoChavePix = 4 THEN 'Telefone'
//  WHEN E.TipoChavePix = 5 THEN 'Aleatória'
// ELSE 
//  'Chave Pix Não Cadastrada' 
// END 
//  as TipoChavePix,
//  (SELECT f_vagasLocacao(e.EstacionamentoId, Now(),'D')) > 0 as VagasReserva
// from Estacionamento E
// left join cidade c on (c.cidadeid = e.cidadeid)
// left join empresa em on (em.empresaid = e.empresaid)
// left join fotoestacionamento fe on (fe.estacionamentoid = e.estacionamentoid and fe.EstacionamentoId)
// where 1 = 1
// and (SELECT f_SituacaoEmpresa(e.EmpresaId,1) < 3
// and (SELECT f_vagasLocacao(e.EstacionamentoId, Now(),'D')) > 0)
// and EXISTS (
// SELECT *
// FROM diasatendimento da
// where da.EstacionamentoId = e.EstacionamentoId
// and da.Dia = WEEKDAY(NOW())
// AND da.HoraEntrada <= DATE_FORMAT(NOW(), '%H:%i:%s')  AND da.HoraSaida > DATE_FORMAT(NOW(), '%H:%i:%s')
// and da.Aberto = 'S'
// )
// ORDER BY E.NomeEstacionamento