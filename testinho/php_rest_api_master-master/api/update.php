<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/contato.php';

$database = new Database();
$conn = $database->getConnection();
$contato = new Contato($conn);

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if ($data === null) {
        http_response_code(400);
        echo json_encode(array("message" => "Erro na decodificação do JSON de entrada."));
    } else {
        if (
            isset($data->contato) &&
            isset($data->contato->id) && 
            !empty($data->contato->nome) &&
            !empty($data->contato->sobrenome) &&
            !empty($data->contato->data_nascimento) &&
            !empty($data->contato->telefone) &&
            !empty($data->contato->celular) &&
            !empty($data->contato->email) &&
            isset($data->empresa) &&
            !empty($data->empresa->nome) &&
            isset($data->empresa->id)
        ) {
            if ($contato->updateContato(
                $data->contato->id,
                $data->contato->nome,
                $data->contato->sobrenome,
                $data->contato->data_nascimento,
                $data->contato->telefone,
                $data->contato->celular,
                $data->contato->email,
                $data->empresa->id,
                $data->empresa->nome 
            )) {
                http_response_code(200);
                echo json_encode(array("message" => "Contato atualizado com sucesso."));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Não foi possível atualizar o contato."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos ou ID de contato ou ID de empresa ausentes. Certifique-se de fornecer todos os campos necessários."));
        }
    }
}
