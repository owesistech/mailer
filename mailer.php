<?php

/**
 * SMTP Email API
 * Requirements:
 * composer require phpmailer/phpmailer
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


require __DIR__ . '/bootstrap.php';

$mail->isSMTP();
$mail->Host       = $_ENV['SMTP_HOST'];
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV['SMTP_USER'];
$mail->Password   = $_ENV['SMTP_PASS'];
$mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
$mail->Port       = $_ENV['SMTP_PORT'];

$mail->setFrom(
    $_ENV['MAIL_FROM_EMAIL'],
    $_ENV['MAIL_FROM_NAME']
);

$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($requestUri !== 'send') {
    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Endpoint not found"
    ]);

    exit;
}

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Only POST method allowed"
    ]);
    exit;
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$input = json_decode(file_get_contents("php://input"), true) ?? [];

/**
 * Required fields validation
 */
$requiredFields = ['from', 'to', 'subject', 'body'];


// Decode Base64 body
$data = base64_decode(json_decode($input), true);

if ($data === false) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Base64 data"
    ]);
    exit;
}

$missingFields = array_filter($requiredFields, function ($field) use ($data) {
    return empty($input[$field]);
});

if (!empty($missingFields)) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields",
        "missing" => array_values($missingFields)
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {

    /*
    |--------------------------------------------------------------------------
    | SMTP CONFIGURATION
    |--------------------------------------------------------------------------
    */

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    /*
    |--------------------------------------------------------------------------
    | EMAIL
    |--------------------------------------------------------------------------
    */

    $mail->setFrom($data->from);
    $mail->addAddress($data->to);

    $mail->isHTML(true);
    $mail->Subject = $data->subject;
    $mail->Body    = $data->body;

    // Optional plain text fallback
    $mail->AltBody = strip_tags($data->body);

    /*
    |--------------------------------------------------------------------------
    | SEND
    |--------------------------------------------------------------------------
    */

    $mail->send();

    echo json_encode([
        "success" => true,
        "message" => "Email sent successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $mail->ErrorInfo
    ]);
}