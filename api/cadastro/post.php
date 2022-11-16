<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "atualizar") {

    $dadosAtualizarCadastro = json_decode(file_get_contents('php://input'), true);
    $sqlAtualizarCadastro = "UPDATE CADASTRO SET ";
    
    if (is_array($dadosAtualizarCadastro)){

        $CadastroId = "";
        $BairroEndereco = "";
        $CidadeId = "";
        $Complemento = "";
        $Cpf = "";
        $Endereco = "";
        $Nome = "";
        $NumeroCelular = "";
        $NumeroCep = "";
        $NumeroEndereco = "";
        $NumeroTelefone = "";

        if (isset($dadosAtualizarCadastro['CadastroId'])){
            $CadastroId =  $dadosAtualizarCadastro['CadastroId'];
        }

        if (isset($dadosAtualizarCadastro['BairroEndereco'])){
            $BairroEndereco =  $dadosAtualizarCadastro['BairroEndereco'];
        }

        if (isset($dadosAtualizarCadastro['CidadeId']['CidadeId'])){
            $CidadeId =  $dadosAtualizarCadastro['CidadeId']['CidadeId'];
        }

        if (isset($dadosAtualizarCadastro['Complemento'])){
            $Complemento =  $dadosAtualizarCadastro['Complemento'];
        }

        if (isset($dadosAtualizarCadastro['Cpf'])){
            $Cpf =  $dadosAtualizarCadastro['Cpf'];
        }

        if (isset($dadosAtualizarCadastro['Endereco'])){
            $Endereco =  $dadosAtualizarCadastro['Endereco'];
        }

        if (isset($dadosAtualizarCadastro['Nome'])){
            $Nome =  $dadosAtualizarCadastro['Nome'];
        }

        if (isset($dadosAtualizarCadastro['NumeroCelular'])){
            $NumeroCelular =  $dadosAtualizarCadastro['NumeroCelular'];
        }

        if (isset($dadosAtualizarCadastro['NumeroCep'])){
            $NumeroCep =  $dadosAtualizarCadastro['NumeroCep'];
        }

        
        if (isset($dadosAtualizarCadastro['NumeroEndereco'])){
            $NumeroEndereco =  $dadosAtualizarCadastro['NumeroEndereco'];
        }

        
        if (isset($dadosAtualizarCadastro['NumeroTelefone'])){
            $NumeroTelefone =  $dadosAtualizarCadastro['NumeroTelefone'];
        }

        $sqlAtualizarCadastro .= " BairroEndereco = '" . $BairroEndereco . "'";
        $sqlAtualizarCadastro .= ", CidadeId = " . $CidadeId;
        $sqlAtualizarCadastro .= ", Complemento = '" . $Complemento . "'";
        $sqlAtualizarCadastro .= ", Cpf = '" . $Cpf . "'";
        $sqlAtualizarCadastro .= ", Endereco = '" . $Endereco . "'";
        $sqlAtualizarCadastro .= ", Nome = '" . $Nome . "'";
        $sqlAtualizarCadastro .= ", NumeroCelular = '" . $NumeroCelular . "'";
        $sqlAtualizarCadastro .= ", NumeroCep = '" . $NumeroCep . "'";
        $sqlAtualizarCadastro .= ", NumeroEndereco = '" . $NumeroEndereco. "'";
        $sqlAtualizarCadastro .= ", NumeroTelefone = '" . $NumeroTelefone . "'";
        $sqlAtualizarCadastro .=  " WHERE CADASTROID = " . $CadastroId;
    }
    
    $db = DB::connect();
    $rs = $db->prepare($sqlAtualizarCadastro);
    

    if ($rs->execute() === false){
        echo json_decode(["dados" => $rs->errorInfo()]);
    } else {
        echo json_encode(["dados" => 'Cadastro atualizado com sucesso.']);
    }

}