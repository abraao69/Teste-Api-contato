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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->contato->nome) &&
        !empty($data->contato->sobrenome) &&
        !empty($data->contato->data_nascimento) &&
        !empty($data->contato->telefone) &&
        !empty($data->contato->celular) &&
        !empty($data->contato->email) &&
        !empty($data->empresa->nome)
    ) {
        if ($contato->createEmpresaAndContato($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "Contato criado com sucesso."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Não foi possível criar o contato."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incompletos. Certifique-se de fornecer todos os campos necessários."));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filtro'])) {
    $empresa = isset($_GET['empresa']) ? $_GET['empresa'] : null;
    $nome_sobrenome = isset($_GET['nome_sobrenome']) ? $_GET['nome_sobrenome'] : null;
    $telefone = isset($_GET['telefone']) ? $_GET['telefone'] : null;
    $celular = isset($_GET['celular']) ? $_GET['celular'] : null;
    $email = isset($_GET['email']) ? $_GET['email'] : null;
    $result = $contato->filterContatos($empresa, $nome_sobrenome, $telefone, $celular, $email);

    echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
}
