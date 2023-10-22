<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/contato.php';

$database = new Database();
$db = $database->getConnection();

$item = new Contato($db);

// Verifique se o ID foi passado na solicitação
$itemId = isset($_GET['id']) ? $_GET['id'] : die();

// Chame a função getContactById para buscar o contato e o nome da empresa
$contactData = $item->getContactById($itemId);

if ($contactData) {
    // O contato foi encontrado, você pode manipular os dados como necessário
    $contatoArr = array(
        "message" => "Contato encontrado",
        "itemCount" => 1,
        "body" => $contactData
    );

    http_response_code(200);
    echo json_encode($contatoArr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Contato não encontrado."));
}


?>
