<?php
/**
 * API endpoint para geração de QR Code PIX em formato SVG
 */

// Carrega o Composer autoloader
require '../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeNone;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Color\Color;

// Verifica o método da requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json; charset=UTF-8');
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
        header('Content-Type: application/json; charset=UTF-8');
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
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Parâmetro pixCopiaECola é obrigatório']);
    exit;
}

try {
    // Define opções para o SvgWriter - versão simplificada sem constantes específicas
    $writerOptions = [];

    // Cria o objeto QR code usando SVG writer
    $result = Builder::create()
        ->writer(new SvgWriter())
        ->writerOptions($writerOptions)
        ->data($pixCopiaECola)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(new RoundBlockSizeModeNone()) // Melhor para SVG
        ->foregroundColor(new Color(0, 0, 0))
        ->backgroundColor(new Color(255, 255, 255))
        ->build();
    
    // Configurar os cabeçalhos para SVG
    header('Content-Type: ' . $result->getMimeType());
    // Opcional: permitir cache da imagem por 1 hora
    header('Cache-Control: public, max-age=3600');
    // Recomendar um nome de arquivo para download
    header('Content-Disposition: inline; filename="pix-qrcode.svg"');
    
    // Exibir o SVG diretamente
    echo $result->getString();
    
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Erro ao gerar QR code: ' . $e->getMessage()]);
}
