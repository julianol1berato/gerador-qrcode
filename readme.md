# Gerador de QR Code

Um gerador de QR Code simples e eficiente desenvolvido em PHP 8.2, inspirado no QRCode Fácil.

## Recursos

- Gerar QR Codes para URLs
- Gerar QR Codes para textos
- Gerar QR Codes para contatos (vCard)
- Gerar QR Codes para redes Wi-Fi

## Requisitos

- PHP 8.2 ou superior
- Composer
- Extensão GD do PHP (para geração de imagens)

## Instalação

1. Clone ou baixe este repositório
2. Navegue até a pasta do projeto e instale as dependências:

```bash
composer install
```

3. Certifique-se de que o diretório `qrcodes` existe e possui permissões de escrita:

```bash
mkdir -p qrcodes
chmod 755 qrcodes
```

4. Inicie o servidor embutido do PHP (ou configure um servidor web como Apache/Nginx):

```bash
php -S localhost:8000
```

5. Acesse a aplicação em seu navegador: `http://localhost:8000`

## Estrutura do Projeto

```
gerador-qrcode/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
├── qrcodes/             # Diretório onde os QR codes gerados são salvos
├── vendor/              # Dependências (gerado pelo Composer)
├── composer.json        # Configuração do Composer
├── gerar-qrcode.php     # Processador que gera os QR codes
└── index.php            # Página principal
```

## Como Usar

1. Acesse a página inicial
2. Escolha o tipo de QR Code que deseja gerar (URL, texto, contato ou Wi-Fi)
3. Preencha os dados no formulário
4. Clique em "Gerar QR Code"
5. Use os botões "Baixar QR Code" ou "Abrir QR Code" para salvar ou visualizar a imagem gerada

## Tecnologias Utilizadas

- PHP 8.2
- Biblioteca Endroid/QR-Code
- Bootstrap 5
- JavaScript/Fetch API

## Licença

Este projeto está licenciado sob a licença MIT.
