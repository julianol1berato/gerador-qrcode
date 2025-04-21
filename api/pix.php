<?php
/**
 * API endpoint para geração de QR Code PIX
 */

// Carrega o Composer autoloader
require '../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

// Configura os cabeçalhos para permitir requisições de outros domínios
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Verifica o método da requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use GET ou POST.']);
    exit;
}

// Obtém os dados do PIX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // No caso de POST, lê o corpo da requisição como JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'JSON inválido']);
        exit;
    }
    
    $pixCopiaECola = $data['pixCopiaECola'] ?? null;
} else {
    // No caso de GET, lê o parâmetro da query string
    $pixCopiaECola = $_GET['pixCopiaECola'] ?? null;
}

// Verifica se o dado do PIX foi fornecido
if (empty($pixCopiaECola)) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetro pixCopiaECola é obrigatório']);
    exit;
}

try {
    // Gera um nome de arquivo único baseado em timestamp + hash aleatório
    $filename = 'pix_' . time() . '_' . substr(md5(rand()), 0, 10) . '.png';
    $filepath = '../qrcodes/' . $filename;
    
    // Verifica se o diretório de destino existe
    if (!is_dir('../qrcodes')) {
        mkdir('../qrcodes', 0755, true);
    }
    
    // Cria o objeto QR code
    $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data($pixCopiaECola)
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
    
    // URL relativa do arquivo gerado
    $fileUrl = '/qrcodes/' . $filename;
    
    // Retorna a resposta com as URLs e os dados da imagem
    $response = [
        'success' => true,
        'file' => $fileUrl,
        'data' => 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString()),
        'pixCopiaECola' => $pixCopiaECola
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao gerar QR code: ' . $e->getMessage()]);
}
