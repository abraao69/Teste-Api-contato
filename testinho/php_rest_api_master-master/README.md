# php-rest-api
Desafio API de contatos
O projeto de API de contatos é desenvolver uma API de cadastro de contatos. A API deve ser capaz de criar, atualizar, deletar e listar contatos.

A API pode ser criada com um framework.

A entidade/modelo de contato deve possuir nome, sobrenome, data de nascimento, telefone, celular, e-mail.

Esses contatos devem pertencer a uma outra entidade chamada empresa, a empresa deve conter somente o campo nome.

A API deve receber parametros de filtro para os campos empresa, nome + sobrenome, telefone, celular e e-mail.

O projeto deve ser compatível com PHP 8.

## PHP CRUD API
* `GET - http://localhost:8085/php_rest_api_master-master/api/read.php` Buscar todos os registros
* `GET - localhost/api/single_read.php/?id=2` Buscar registro único
* `POST - http://localhost:8085/php_rest_api_master-master/api/create.php` Criar registro
* `POST - http://localhost:8085/php_rest_api_master-master/api/update.php`Atualizar registro
* `DELETE - http://localhost:8085/php_rest_api_master-master/api/delete.php?id=2` Remover registros

