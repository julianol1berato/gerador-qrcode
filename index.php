<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header class="text-center py-4">
            <h1>Gerador de QR Code</h1>
            <p>Crie QR Codes facilmente para diversos tipos de conteúdo</p>
        </header>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="qrTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="url-tab" data-bs-toggle="tab" data-bs-target="#url" type="button" role="tab" aria-controls="url" aria-selected="true">URL</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text" type="button" role="tab" aria-controls="text" aria-selected="false">Texto</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contato</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wifi-tab" data-bs-toggle="tab" data-bs-target="#wifi" type="button" role="tab" aria-controls="wifi" aria-selected="false">Wi-Fi</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="qrTypeTabsContent">
                            <!-- URL Tab -->
                            <div class="tab-pane fade show active" id="url" role="tabpanel" aria-labelledby="url-tab">
                                <form id="urlForm" action="gerar-qrcode.php" method="post">
                                    <input type="hidden" name="type" value="url">
                                    <div class="mb-3">
                                        <label for="urlInput" class="form-label">Digite a URL:</label>
                                        <input type="url" class="form-control" id="urlInput" name="url" placeholder="https://exemplo.com" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gerar QR Code</button>
                                </form>
                            </div>
                            
                            <!-- Text Tab -->
                            <div class="tab-pane fade" id="text" role="tabpanel" aria-labelledby="text-tab">
                                <form id="textForm" action="gerar-qrcode.php" method="post">
                                    <input type="hidden" name="type" value="text">
                                    <div class="mb-3">
                                        <label for="textInput" class="form-label">Digite o texto:</label>
                                        <textarea class="form-control" id="textInput" name="text" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gerar QR Code</button>
                                </form>
                            </div>
                            
                            <!-- Contact Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <form id="contactForm" action="gerar-qrcode.php" method="post">
                                    <input type="hidden" name="type" value="contact">
                                    <div class="mb-3">
                                        <label for="nameInput" class="form-label">Nome:</label>
                                        <input type="text" class="form-control" id="nameInput" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phoneInput" class="form-label">Telefone:</label>
                                        <input type="tel" class="form-control" id="phoneInput" name="phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="emailInput" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="addressInput" class="form-label">Endereço:</label>
                                        <input type="text" class="form-control" id="addressInput" name="address">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gerar QR Code</button>
                                </form>
                            </div>
                            
                            <!-- Wi-Fi Tab -->
                            <div class="tab-pane fade" id="wifi" role="tabpanel" aria-labelledby="wifi-tab">
                                <form id="wifiForm" action="gerar-qrcode.php" method="post">
                                    <input type="hidden" name="type" value="wifi">
                                    <div class="mb-3">
                                        <label for="ssidInput" class="form-label">Nome da rede (SSID):</label>
                                        <input type="text" class="form-control" id="ssidInput" name="ssid" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwordInput" class="form-label">Senha:</label>
                                        <input type="text" class="form-control" id="passwordInput" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="encryptionInput" class="form-label">Tipo de Criptografia:</label>
                                        <select class="form-control" id="encryptionInput" name="encryption">
                                            <option value="WPA">WPA/WPA2/WPA3</option>
                                            <option value="WEP">WEP</option>
                                            <option value="nopass">Sem Senha</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gerar QR Code</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Resultado</h5>
                    </div>
                    <div class="card-body text-center" id="qrResult">
                        <p>Preencha o formulário e clique em "Gerar QR Code" para ver o resultado aqui.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
