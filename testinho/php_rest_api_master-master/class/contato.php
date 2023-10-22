<?php
class Contato
{
    private $conn;
    private $db_table = "Contato";

    public $id;
    public $nome;
    public $sobrenome;
    public $data_nascimento;
    public $telefone;
    public $celular;
    public $email;
    public $empresa_id;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function CustomFilter($filters)
    {
        $sqlQuery = "SELECT c.id, c.nome, c.sobrenome, c.data_nascimento, c.telefone, c.celular, c.email, e.nome AS nome_empresa
                     FROM Contato c
                     LEFT JOIN Empresa e ON c.empresa_id = e.id
                     WHERE 1=1";

        $params = [];

        if (isset($filters['nome'])) {
            $sqlQuery .= " AND c.nome LIKE '%" . $filters['nome'] . "%'";
        }

        if (isset($filters['sobrenome'])) {
            $sqlQuery .= " AND c.sobrenome LIKE '%" . $filters['sobrenome'] . "%'";
        }

        if (isset($filters['empresa'])) {
            $sqlQuery .= " AND e.nome LIKE '%" . $filters['empresa'] . "%'";
        }

        if (isset($filters['data_nascimento'])) {
            $sqlQuery .= " AND c.data_nascimento = " . $filters['data_nascimento'];
        }
        if (isset($filters['id'])) {
            $sqlQuery .= " AND c.id = " . $filters['id'];
        }

        $stmt = $this->conn->prepare($sqlQuery);

        foreach ($params as $param => $value) {
            $stmt->bindParam($param, $value);
        }

        if ($stmt->execute()) {
            $result = [
                "message" => "Estes são os contatos no banco de dados",
                "itemCount" => $stmt->rowCount(),
                "body" => []
            ];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $c = array(
                    "id" => $row['id'],
                    "nome" => $row['nome'],
                    "sobrenome" => $row['sobrenome'],
                    "data_nascimento" => $row['data_nascimento'],
                    "telefone" => $row['telefone'],
                    "celular" => $row['celular'],
                    "email" => $row['email'],
                    "nome_empresa" => $row['nome_empresa']
                );

                array_push($result["body"], $c);
            }
        } else {
            $result = [
                "message" => "Erro ao executar a consulta SQL.",
                "itemCount" => 0,
                "body" => []
            ];
        }

        return $result;
    }


    public function filterContatos($filters)
    {
        $sqlQuery = "SELECT c.id, c.nome, c.sobrenome, c.data_nascimento, c.telefone, c.celular, c.email, e.nome AS nome_empresa
                     FROM Contato c
                     LEFT JOIN Empresa e ON c.empresa_id = e.id
                     WHERE 1=1";
        $params = [];

        if (isset($filters['contato'])) {
            $contatoFilters = $filters['contato'];

            if (isset($contatoFilters['empresa'])) {
                $sqlQuery .= " AND c.empresa_id = :contato_empresa_id";
                $params[':contato_empresa_id'] = $contatoFilters['empresa'];
            }

            if (isset($contatoFilters['nome_sobrenome'])) {
                $sqlQuery .= " AND CONCAT(c.nome, ' ', c.sobrenome) = :nome_sobrenome";
                $params[':nome_sobrenome'] = $contatoFilters['nome_sobrenome'];
            }
        }

        if (isset($filters['empresa'])) {
            $empresaFilters = $filters['empresa'];

            if (isset($empresaFilters['nome'])) {
                $sqlQuery .= " AND e.nome = :empresa_nome";
                $params[':empresa_nome'] = $empresaFilters['nome'];
            }
        }

        $stmt = $this->conn->prepare($sqlQuery);

        foreach ($params as $param => $value) {
            $stmt->bindParam($param, $value);
        }

        $stmt->execute();

        return $stmt;
    }

    public function createEmpresaAndContato($data)
    {
        if (!empty($data->empresa->nome)) {
            $empresaNome = $data->empresa->nome;
            $empresaId = $this->createEmpresa($empresaNome);

            if ($empresaId !== false) {
                $this->nome = $data->contato->nome;
                $this->sobrenome = $data->contato->sobrenome;
                $this->data_nascimento = $data->contato->data_nascimento;
                $this->telefone = $data->contato->telefone;
                $this->celular = $data->contato->celular;
                $this->email = $data->contato->email;
                $this->empresa_id = $empresaId;

                return $this->createContato($empresaId);
            } else {
                echo json_encode(array('message' => 'Error creating Empresa'));
                exit();
            }
        }
        echo json_encode(array('message' => 'Empty Empresa data'));
        exit();

        return false;
    }

    public function createEmpresa($nome)
    {
        $sqlQuery = "INSERT INTO Empresa (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":nome", $nome);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function createContato($empresaId)
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . "
        SET
        nome = :nome,
        sobrenome = :sobrenome,
        data_nascimento = :data_nascimento,
        telefone = :telefone,
        celular = :celular,
        email = :email,
        empresa_id = :empresa_id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":sobrenome", $this->sobrenome);
        $stmt->bindParam(":data_nascimento", $this->data_nascimento);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":celular", $this->celular);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":empresa_id", $empresaId);
        if ($stmt->execute()) {
            return true;
        } else {
            echo json_encode(array('message' => 'Error creating Contato: ' . implode(', ', $stmt->errorInfo())));
            exit();
        }
    }
    public function getSingleContact()
    {
        $sqlQuery = "SELECT
                        c.id AS contato_id, 
                        c.nome AS contato_nome, 
                        c.sobrenome AS contato_sobrenome, 
                        c.data_nascimento, 
                        c.telefone, 
                        c.celular, 
                        c.email, 
                        c.empresa_id, 
                        e.nome AS empresa_nome
                    FROM
                        Contato c
                    LEFT JOIN Empresa e ON c.empresa_id = e.id
                    WHERE 
                        c.id = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->contato_id = $dataRow['contato_id'];
        $this->contato_nome = $dataRow['contato_nome'];
        $this->contato_sobrenome = $dataRow['contato_sobrenome'];
        $this->data_nascimento = $dataRow['data_nascimento'];
        $this->telefone = $dataRow['telefone'];
        $this->celular = $dataRow['celular'];
        $this->email = $dataRow['email'];
        $this->empresa_id = $dataRow['empresa_id'];
        $this->empresa_nome = $dataRow['empresa_nome'];
    }

    public function getContactById($id)
    {
        $sqlQuery = "SELECT c.id, c.nome, c.sobrenome, c.data_nascimento, c.telefone, c.celular, c.email, c.empresa_id, e.nome AS nome_empresa
                     FROM " . $this->db_table . " c
                     LEFT JOIN Empresa e ON c.empresa_id = e.id
                     WHERE c.id = :id
                     LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow) {
            return $dataRow;
        } else {
            return null;
        }
    }

    public function updateContato($id, $nome, $sobrenome, $data_nascimento, $telefone, $celular, $email, $empresa_id, $novoNomeEmpresa)
    {
        $this->conn->beginTransaction();
        $sqlQueryContato = "UPDATE " . $this->db_table . "
        SET
        nome = :nome,
        sobrenome = :sobrenome,
        data_nascimento = :data_nascimento,
        telefone = :telefone,
        celular = :celular,
        email = :email,
        empresa_id = :empresa_id
        WHERE
        id = :id";

        $stmtContato = $this->conn->prepare($sqlQueryContato);
        $sqlQueryEmpresa = "UPDATE Empresa
        SET
        nome = :novoNomeEmpresa
        WHERE
        id = :empresa_id";

        $stmtEmpresa = $this->conn->prepare($sqlQueryEmpresa);
        $stmtContato->bindParam(":nome", $nome);
        $stmtContato->bindParam(":sobrenome", $sobrenome);
        $stmtContato->bindParam(":data_nascimento", $data_nascimento);
        $stmtContato->bindParam(":telefone", $telefone);
        $stmtContato->bindParam(":celular", $celular);
        $stmtContato->bindParam(":email", $email);
        $stmtContato->bindParam(":empresa_id", $empresa_id);
        $stmtContato->bindParam(":id", $id);
        $stmtEmpresa->bindParam(":novoNomeEmpresa", $novoNomeEmpresa);
        $stmtEmpresa->bindParam(":empresa_id", $empresa_id);

        try {
            $stmtContato->execute();
            $stmtEmpresa->execute();
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollback();
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }


    public function deleteEmployee()
    {
        $this->conn->beginTransaction();
        $sqlQueryGetEmpresaId = "SELECT empresa_id FROM " . $this->db_table . " WHERE id = :id";
        $stmtGetEmpresaId = $this->conn->prepare($sqlQueryGetEmpresaId);
        $stmtGetEmpresaId->bindParam(":id", $this->id);
        $stmtGetEmpresaId->execute();

        if ($stmtGetEmpresaId->rowCount() > 0) {
            $row = $stmtGetEmpresaId->fetch(PDO::FETCH_ASSOC);
            $empresaId = $row['empresa_id'];
            $sqlQueryDeleteContato = "DELETE FROM " . $this->db_table . " WHERE id = :id";
            $stmtDeleteContato = $this->conn->prepare($sqlQueryDeleteContato);
            $stmtDeleteContato->bindParam(":id", $this->id);
            $stmtDeleteContato->execute();
            $sqlQueryDeleteEmpresa = "DELETE FROM Empresa WHERE id = :empresa_id";
            $stmtDeleteEmpresa = $this->conn->prepare($sqlQueryDeleteEmpresa);
            $stmtDeleteEmpresa->bindParam(":empresa_id", $empresaId);
            $stmtDeleteEmpresa->execute();

            $this->conn->commit();
            return true;
        } else {
            $this->conn->rollBack();
            return false;
        }
    }
}
