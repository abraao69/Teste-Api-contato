#!/bin/bash

# Caminho para o arquivo de origem
origem="database.php"

# Caminho para o diretório de destino
destino="./siseducarraial/application/config"

# Verifica se o arquivo de destino já existe
if [ -e "$destino/database.php" ]; then
    echo "O arquivo database.php já existe no diretório de destino."
    read -p "Deseja substituí-lo? (S/N): " substituir
    if [ "$substituir" = "S" ] || [ "$substituir" = "s" ]; then
        cp "$origem" "$destino"
        echo "Arquivo copiado com sucesso."
    else
        echo "Operação cancelada."
    fi
else
    cp "$origem" "$destino"
    echo "Arquivo copiado com sucesso."
fi
