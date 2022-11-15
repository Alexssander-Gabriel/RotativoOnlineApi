<?php

if ($acao == ''){
     json_encode(['ERRO' => 'Caminho não encontrado!']);                
} else if ($acao == "adiciona" && $param == '') {
    //$sql = "INSERT INTO LOGIN (";

    // $contadorCampos = 1;
    // foreach (array_keys($_POST) as $indice => $value) {
    //     if (count($_POST) > $contadorCampos){
    //         $sql .= "{$value},";
    //     } else {
    //         $sql .= "{$value}";
    //     }
    //     $contadorCampos ++;
    // }

    // $sql .= ") VALUES (";

    // $contadorValores = 1;
    // foreach (array_values($_POST) as $indice => $value) {
    //     //estruturaValoresInsert($value);
    //     if ($contadorValores == 4){
    //         if (count($_POST) > $contadorValores){
    //             $sql .= "{$value},";
    //         } else {
    //             $sql .= "{$value}";
    //         }
    //     } else {
    //         if (count($_POST) > $contadorValores){
    //             $sql .= "'{$value}',";
    //         } else {
    //             $sql .= "'{$value}'";
    //         }
    //     }
    //     $contadorValores ++;
    //}

    //$sql .= ")";

    //echo "deu ruim caralho";

    //echo json_encode ($_POST);
    
    // $db = DB::connect();
    // $rs = $db->prepare($sql);
    // $exec = $rs->execute();

    // if ($exec){
    //     echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
    // } else {
    //     echo json_encode(["dados" => 'Não existem dados para retornar']);
    // }
    
    //$jsonData = json_encode($_POST, JSON_FORCE_OBJECT);
    //$jsonDados = json_decode($jsonData);
    //echo $jsonData;
    //insereLogin(json_encode($_POST));

    //$data = json_decode(json_encode($_POST));
    // $senha = "";
    //$nome = "";

    // foreach($data as $resultado) {
    //     $nome = $resultado->name;
    //     $senha = $resultado->password;
    // }
    //insereLogin($nome, $senha);
    //print_r(json_encode($_POST));

    $dadosLogin = json_decode(file_get_contents('php://input'), true);

    $sqlCadastro = "INSERT INTO CADASTRO (";
    $sqlCadastro .= "Nome,NumeroTelefone,Cpf,NumeroCep,CidadeId,NumeroEndereco,Endereco,BairroEndereco,Complemento";
    if (is_array($dadosLogin)){
        // $contadorCamposCadastro = 1;
        // foreach (array_keys($dadosLogin) as $indice => $value) {
        //     if ($contadorCamposCadastro > 3){
        //         if (count($dadosLogin) > $contadorCamposCadastro){
        //             $sqlCadastro .= "{$value},";
        //         } else {
        //             $sqlCadastro .= "{$value}";
        //         }
        //     }
        //     $contadorCamposCadastro ++;
        // }

        $sqlCadastro .= ") VALUES (";

        $contadorValoresCadastro = 1;
        foreach (array_values($dadosLogin) as $indice => $value) {
            if ($contadorValoresCadastro > 3){
                if ($contadorValoresCadastro == 8){
                    $cidade = $value['CidadeId'];
                    $sqlCadastro .= "{$cidade},";
                } else {
                    if (count($dadosLogin) > $contadorValoresCadastro){
                        $sqlCadastro .= "'{$value}',";
                    } else {
                        $sqlCadastro .= "'{$value}'";
                    }
                }
            }
            $contadorValoresCadastro ++;
        } 
    }
    $sqlCadastro .= ")";

    //print_r($sqlCadastro);
    $dbCadastro = DB::connect();
    $rsCadastro = $dbCadastro->prepare($sqlCadastro);
    $execCadastro = $rsCadastro->execute();

    $CadastroId = 0;
    
    $rsCadastrado = $dbCadastro->prepare("SELECT * FROM CADASTRO ORDER BY CADASTROID DESC");
    $rsCadastrado->execute();
    $objCadastrado = $rsCadastrado->fetchObject();

    $CadastroId = $objCadastrado->CadastroId;
    
    //print_r($CadastroId);

    // $nome = "";
    // $senha = "";
    // $email = "";

    $sqlLogin = "INSERT INTO LOGIN(CadastroId,PermissaoId,NomeUsuario,Email,Senha) ";
    $sqlLogin  .= "VALUES(";
    $sqlLogin  .=  "{$CadastroId}";
    $sqlLogin  .=  ",4";
    $contadorValores = 1;
    if (is_array($dadosLogin)){
        foreach (array_values($dadosLogin) as $indice => $value) {
            if ($contadorValores == 1){
                $sqlLogin  .= ",'{$value}'";
            } else if ($contadorValores == 2){
                $sqlLogin  .= ",'{$value}'";
            } else if ($contadorValores == 3){
                $sqlLogin .= ",'{$value}'";
            } else {
                break;
            }
            $contadorValores++;
        }    
    }  
    $sqlLogin  .= ")";
        
    $db = DB::connect();
    $rs = $db->prepare($sqlLogin);
    $exec = $rs->execute();

    if ($exec){
        echo json_encode(["dados" => 'Dados foram inseridos com sucesso!']);
    } else {
        echo json_encode(["dados" => 'Não existem dados para retornar']);
    }
} else if ($acao == "atualizar") {
    $dadosAtualizarLogin = json_decode(file_get_contents('php://input'), true);
    $sqlAtualizarLogin = "UPDATE LOGIN SET ";
    
    if (is_array($dadosAtualizarLogin)){

        $LoginId = "";
        $NomeUsuario = "";
        $Email = "";
        $Senha = "";
        $AlteraSenha = "N";

        if (isset($dadosAtualizarLogin['LoginId'])){
            $LoginId =  $dadosAtualizarLogin['LoginId'];
        }

        if (isset($dadosAtualizarLogin['NomeUsuario'])){
            $NomeUsuario =  $dadosAtualizarLogin['NomeUsuario'];
        }

        if (isset($dadosAtualizarLogin['Email'])){
            $Email =  $dadosAtualizarLogin['Email'];
        }

        if (isset($dadosAtualizarLogin['SenhaNova'])){
            $Senha =  $dadosAtualizarLogin['SenhaNova'];
        }

        if (isset($dadosAtualizarLogin['AlteraSenha'])){
            $AlteraSenha =  $dadosAtualizarLogin['AlteraSenha'];
        }

        $sqlAtualizarLogin .= " NomeUsuario = '" . $NomeUsuario . "'";
        $sqlAtualizarLogin .= " ,Email = '" . $Email . "'";
        if ($AlteraSenha == 'S'){
            $sqlAtualizarLogin .= " ,Senha = '" . $Senha . "'";
        }
        $sqlAtualizarLogin .= " WHERE LOGINID = " . $LoginId;
    }

    //print_r($sqlAtualizarLogin);
        
    $db = DB::connect();
    $rs = $db->prepare($sqlAtualizarLogin);
    

    if ($rs->execute() === false){
        echo json_decode(["dados" => $rs->errorInfo()]);
    } else {
        echo json_encode(["dados" => 'Login atualizado com sucesso.']);
    }
}