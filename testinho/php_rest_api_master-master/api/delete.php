<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../config/database.php';
include_once '../class/contato.php';

$database = new Database();
$db = $database->getConnection();
$item = new Contato($db);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id !== null) {
        $item->id = $id;

        $deleteResult = $item->deleteEmployee();

        if ($deleteResult === true) {
            http_response_code(200);
            echo json_encode(array("message" => "Contato e empresa vinculada excluídos com sucesso."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Não foi possível excluir o contato e a empresa vinculada. Erro: " . $deleteResult));
            die();
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "ID do contato ausente na URL. Certifique-se de fornecer o ID do contato na URL."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Método não permitido. Use DELETE para excluir um contato e a empresa vinculada."));
}
