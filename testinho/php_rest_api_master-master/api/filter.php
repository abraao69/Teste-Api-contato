<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/contato.php';

$database = new Database();
$conn = $database->getConnection();

$contato = new Contato($conn);

$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(array("message" => "Dados JSON inválidos."));
} else {
    $result = $contato->CustomFilter($data);

    if ($result["itemCount"] > 0) {
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Nenhum registro encontrado.")
        );
    }
}
