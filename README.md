# Miaversa

Site miaversa.

## Requisitos

  - PHP 7.2 (testado com)
  - nu html5 validator versão 17.11.1
  - AWS cli - aws-cli/1.14.19 Python/3.5.2 Linux/4.10.0-42-generic botocore/1.8.23

No diretório `bin` encontra-se os executáveis.

O comando `smake` gera o site e espera uma variável de ambiente chamada `SALT`.
O `SALT` serve para assinar as chamadas para o carrinho de compras de forma segura.

### Exemplo de chamada do comando

    SALT=<super-seguro> bin\smake

O comando `html5validator` é um atalho para chamar o validador nu.

O comando `update` é um atalho para atualizar o bucket S3.
