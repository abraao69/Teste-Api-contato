<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/contato.php';

$database = new Database();
$conn = $database->getConnection();

$contato = new Contato($conn);

$empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '';
$nome_sobrenome = isset($_GET['nome_sobrenome']) ? $_GET['nome_sobrenome'] : '';
$telefone = isset($_GET['telefone']) ? $_GET['telefone'] : '';
$celular = isset($_GET['celular']) ? $_GET['celular'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

fetchContatos($contato, $empresa, $nome_sobrenome, $telefone, $celular, $email);

function fetchContatos($contato, $empresa, $nome_sobrenome, $telefone, $celular, $email) {
    $stmt = $contato->filterContatos($empresa, $nome_sobrenome, $telefone, $celular, $email);
    $itemCount = $stmt->rowCount();
    
    if ($itemCount > 0) {
        $contatoArr = array();
        $contatoArr["message"] = "Estes são os contatos no banco de dados";
        $contatoArr["itemCount"] = $itemCount;
        $contatoArr["body"] = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
        
            // Use a função getSingleContact para obter os detalhes do contato e empresa
            $contato->id = $id;
            $contato->getSingleContact();
    
            $c = array(
                "id" => $contato->contato_id,
                "nome" => $contato->contato_nome,
                "sobrenome" => $contato->contato_sobrenome,
                "data_nascimento" => $contato->data_nascimento,
                "telefone" => $contato->telefone,
                "celular" => $contato->celular,
                "email" => $contato->email,
                "empresa_id" => $contato->empresa_id,
                "empresa_nome" => $contato->empresa_nome,
            );
        
            array_push($contatoArr["body"], $c);
        }
        
        echo json_encode($contatoArr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Nenhum registro encontrado.")
        );
    }
}
