<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * 1. LOAD COMPOSER FIRST
 */
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * 2. LOAD ENV
 */
require __DIR__ . '/bootstrap.php';

/**
 * 3. CHECK ROUTE
 */
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($requestUri !== 'send') {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Endpoint not found"
    ]);
    exit;
}

/**
 * 4. ONLY POST
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Only POST method allowed"
    ]);
    exit;
}

/**
 * 5. READ INPUT
 */
$input = json_decode(file_get_contents("php://input"), true) ?? [];

/**
 * 6. VALIDATION
 */
$requiredFields = ['name', 'to', 'subject', 'body'];

$missingFields = array_filter($requiredFields, fn($f) => empty($input[$f]));

if (!empty($missingFields)) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields",
        "missing" => array_values($missingFields)
    ]);
    exit;
}

/**
 * 7. DECODE BASE64 BODY
 */
$data = is_array($input) ? $input : base64_decode($input['data'], true);

$name = isset($input['data']) ? $data->name : $data['name'];
$to = isset($input['data']) ? $data->to : $data['to'];
$subject = isset($input['data']) ? $data->subject : $data['subject'];
$body = isset($input['data']) ? $data->body : $data['body'];

if ($data === false) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Base64 body"
    ]);
    exit;
}

/**
 * 8. INIT MAILER (ONLY ONCE)
 */
$mail = new PHPMailer(true);

$subject = !empty($subject) ? '200 CEOs Business Forum' : $subject;
$message = $body;

require_once 'template.php';

try {

    // SMTP CONFIG
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
    $mail->Port       = $_ENV['SMTP_PORT'];

    // FROM
    $mail->setFrom(
        $_ENV['MAIL_FROM_EMAIL'],
        $_ENV['MAIL_FROM_NAME']
    );
    // TO
    $mail->addAddress($to);
    // CONTENT
    $mail->isHTML();
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($body);

    // SEND
    $mail->send();

    echo json_encode([
        "success" => true,
        "message" => "Email sent successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $mail->ErrorInfo ?? $e->getMessage()
    ]);
}