<?php
// Файл: sendmail.php

// 1. Подключаем PHPMailer
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Получаем данные из формы
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$city = $_POST['city'] ?? '';
$message = $_POST['message'] ?? '';

// 3. Проверяем обязательные поля
if (empty($name) || empty($phone)) {
    echo json_encode([
        'success' => false, 
        'message' => '❌ Заполните имя и телефон'
    ]);
    exit;
}

// 4. Создаем письмо
$mail = new PHPMailer(true);

try {
    // === НАСТРОЙКИ ДЛЯ ЯНДЕКСА (ЗАМЕНИТЕ!) ===
    $mail->isSMTP();
    $mail->Host = 'smtp.yandex.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'golubev.vladi44ir@yandex.ru'; // ЗАМЕНИТЕ НА СВОЮ
    $mail->Password = 'yandexGOVLGolubev113';           // ЗАМЕНИТЕ НА СВОЙ
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';
    
    // От кого
    $mail->setFrom('golubev.vladi44ir@yandex.ru', 'Shustoffdesign');
    
    // Кому (куда придут заявки)
    $mail->addAddress('info@shustoffdesign.ru'); // Основная почта
    
    // Тема письма
    $mail->Subject = "Заявка с сайта Shustoffdesign от $name";
    
    // Тело письма (HTML)
    $mail->isHTML(true);
    $mail->Body = "
    <h2>📨 Новая заявка с сайта Shustoffdesign</h2>
    <p><strong>👤 Имя:</strong> $name</p>
    <p><strong>📞 Телефон:</strong> $phone</p>
    <p><strong>🏙️ Город:</strong> $city</p>
    <p><strong>💬 Сообщение:</strong><br>$message</p>
    <hr>
    <p><em>📅 Отправлено: " . date('d.m.Y H:i') . "</em></p>
    ";
    
    // Отправляем
    if ($mail->send()) {
        echo json_encode([
            'success' => true, 
            'message' => '✅ Заявка отправлена! Мы свяжемся с вами в ближайшее время.'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => '❌ Ошибка отправки. Попробуйте позже.'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => '❌ Ошибка на сервере. Попробуйте позже.'
    ]);
}
?>