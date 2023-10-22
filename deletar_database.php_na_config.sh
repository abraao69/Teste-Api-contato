#!/bin/bash

# Caminho para o arquivo a ser verificado
arquivo="./siseducarraial/application/config/database.php"

# Verifica se o arquivo existe
if [ -e "$arquivo" ]; then
    echo "O arquivo database.php existe no diretório."
    read -p "Deseja excluí-lo? (S/N): " excluir
    if [ "$excluir" = "S" ] || [ "$excluir" = "s" ]; then
        rm "$arquivo"
        echo "Arquivo excluído com sucesso."
    else
        echo "Operação cancelada."
    fi
else
    echo "O arquivo database.php não existe no diretório."
fi
