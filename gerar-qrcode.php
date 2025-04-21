<?php
/**
 * Gerador de QR Code
 * Processa as solicitações de geração de QR code
 */

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Método não permitido');
}

// Carrega o Composer autoloader
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

// Processa o tipo de QR code solicitado
$type = $_POST['type'] ?? '';
$content = '';

switch ($type) {
    case 'url':
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            die(json_encode(['error' => 'URL inválida']));
        }
        $content = $url;
        break;
        
    case 'text':
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (empty($text)) {
            die(json_encode(['error' => 'Texto não pode estar vazio']));
        }
        $content = $text;
        break;
        
    case 'contact':
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // Formato vCard
        $vcard = "BEGIN:VCARD\nVERSION:3.0\n";
        $vcard .= "N:{$name};;;\n";
        $vcard .= "FN:{$name}\n";
        
        if (!empty($phone)) {
            $vcard .= "TEL;TYPE=CELL:{$phone}\n";
        }
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $vcard .= "EMAIL:{$email}\n";
        }
        
        if (!empty($address)) {
            $vcard .= "ADR:;;{$address};;;\n";
        }
        
        $vcard .= "END:VCARD";
        $content = $vcard;
        break;
        
    case 'wifi':
        $ssid = filter_input(INPUT_POST, 'ssid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $encryption = filter_input(INPUT_POST, 'encryption', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // Formato Wi-Fi
        $wifi = "WIFI:S:{$ssid};";
        $wifi .= "T:{$encryption};";
        
        if (!empty($password) && $encryption !== 'nopass') {
            $wifi .= "P:{$password};";
        }
        
        $wifi .= ";";
        $content = $wifi;
        break;
        
    default:
        die(json_encode(['error' => 'Tipo de QR code não suportado']));
}

try {
    // Determina o nome do arquivo (baseado no timestamp)
    $filename = 'qrcode_' . time() . '.png';
    $filepath = 'qrcodes/' . $filename;
    
    // Verifica se o diretório de destino existe
    if (!is_dir('qrcodes')) {
        mkdir('qrcodes', 0755, true);
    }
    
    // Cria o objeto QR code
    $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($content)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->foregroundColor(new Color(0, 0, 0))
        ->backgroundColor(new Color(255, 255, 255))
        ->build();
    
    // Salva o QR code em um arquivo
    $result->saveToFile($filepath);
    
    // Retorna o caminho do arquivo e os dados da imagem
    $response = [
        'success' => true,
        'file' => $filepath,
        'data' => 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString())
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro ao gerar QR code: ' . $e->getMessage()]);
}
