# Miaversa

Site miaversa.

## Requisitos

  - PHP 7.2 (testado com)
  - nu html5 validator versão 17.11.1
  - AWS cli - aws-cli/1.14.19 Python/3.5.2 Linux/4.10.0-42-generic botocore/1.8.23
  - crass css compressor
  - jpegtran

No diretório `bin` encontra-se os executáveis.

O comando `smake` gera o site e espera uma variável de ambiente chamada `SALT`.
O `SALT` serve para assinar as chamadas para o carrinho de compras de forma segura.
A variável de ambiente `DEBUG` pode ser utilizada no ambiente de desenvolvimento para reduzir o tempo
de processamento, eliminando a etapa de compressão do CSS.

### Exemplo de chamada do comando

    SALT=<super-seguro> bin\smake

O comando `html5validator` é um atalho para chamar o validador nu.

O comando `update` é um atalho para atualizar o bucket S3.

Para o estilo css é preciso instalar o nodejs e depois o crass:

    npm install --save-dev crass

Para reduzir o tamanho das imagens é preciso instalar o jpegtran:

    sudo apt install libjpeg-progs
