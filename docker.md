# Docker para Gerador de QR Code

Este diretório contém a configuração Docker para executar o Gerador de QR Code em um ambiente isolado.

## Requisitos

- Docker
- Docker Compose

## Como usar

1. Na raiz do projeto, execute o comando:

```bash
docker-compose up -d
```

2. Acesse a aplicação em seu navegador através da URL:

```
http://localhost:8080
```

3. Para parar os containers:

```bash
docker-compose down
```

## Informações adicionais

- A aplicação estará rodando na porta 8080
- Os arquivos do projeto são montados como volume, então qualquer alteração nos arquivos será refletida automaticamente
- A pasta vendor é excluída do volume para evitar problemas de compatibilidade
