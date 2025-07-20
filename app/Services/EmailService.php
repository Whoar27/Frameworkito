<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Helpers\UserAgentParser;

class EmailService {
    protected $config;

    public function __construct(array $config = []) {
        // Cargar config desde app/Config/mail.php si no se pasa
        $this->config = $config ?: require __DIR__ . '/../Config/mail.php';
    }

    /**
     * Envía un correo de recuperación de contraseña
     */
    public function sendPasswordReset($toEmail, $toName, $resetLink, $companyName) {
        $fromName = "[Seguridad] Enlace de recuperación de contraseña";
        $subject = 'Recupera tu contraseña';
        $templatePath = __DIR__ . '/../Views/templates/email/forgot-password.html';

        // Quitar colillas "" de $companyName
        $companyName = str_replace(['"', '"'], '', $companyName);
        $body = $this->renderTemplate($templatePath, [
            'APP_URL' => $_ENV['APP_URL'],
            'COMPANY_NAME' => $companyName,
            'NAME_USER' => $toName,
            'RESET_LINK' => $resetLink,
            'EMAIL_SUPPORT' => $_ENV['SUPPORT_EMAIL']
        ]);
        return $this->send($fromName, $toEmail, $toName, $subject, $body);
    }

    /**
     * Envía un correo de confirmación de cambio de contraseña
     */
    public function sendPasswordChanged($toEmail, $toName, $companyName = 'Frameworkito') {
        $fromName = "[Seguridad] Confirmación cambio de contraseña";
        $subject = 'Tu contraseña ha sido cambiada';
        $templatePath = __DIR__ . '/../Views/templates/email/password-changed.html';

        // Quitar colillas "" de $companyName
        $companyName = str_replace(['"', '"'], '', $companyName);
        $now = new \DateTime('now', new \DateTimeZone($_ENV['APP_TIMEZONE']));
        $body = $this->renderTemplate($templatePath, [
            'APP_URL' => $_ENV['APP_URL'],
            'COMPANY_NAME' => $companyName,
            'NAME_USER'    => $toName,
            'CHANGE_DATE'  => $now->format('d/m/Y'),
            'CHANGE_TIME'  => $now->format('H:i:s'),
            'USER_IP'      => $_SERVER['REMOTE_ADDR'] ?? 'Desconocida',
            'USER_DEVICE'  => UserAgentParser::parseDevice(),
            'EMAIL_SUPPORT' => $_ENV['SUPPORT_EMAIL']
        ]);
        file_log('info', 'Correo de confirmación de cambio de contraseña a punto de ser enviado', [
            'email' => $toEmail,
            'device_info' => UserAgentParser::getDetailedInfo()
        ]);
        return $this->send($fromName, $toEmail, $toName, $subject, $body);
    }

    /**
     * Envía un email genérico usando PHPMailer
     */
    public function send($fromName, $toEmail, $toName, $subject, $body) {
        // Validar configuración crítica
        $required = [
            'smtp.host',
            'smtp.username',
            'smtp.password',
            'smtp.encryption',
            'smtp.port',
            'from.address',
            'from.name'
        ];
        foreach ($required as $key) {
            $parts = explode('.', $key);
            $value = $this->config;
            foreach ($parts as $p) {
                if (!isset($value[$p]) || $value[$p] === '' || $value[$p] === false) {
                    $msg = "EmailService: Falta o vacío '$key' (" . print_r($value[$p], true) . ") para enviar correo";
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    $_SESSION['_flash']['email_error'] = $msg;
                    return false;
                }
                $value = $value[$p];
            }
        }

        $mail = new PHPMailer(true);
        try {
            // Config SMTP
            $mail->isSMTP();
            $mail->Host = $this->config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['smtp']['username'];
            $mail->Password = $this->config['smtp']['password'];
            // Traducir 'ssl'/'tls' a constantes PHPMailer
            $encryption = strtolower(trim($this->config['smtp']['encryption'] ?? ''));
            if ($encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = false;
            }
            $mail->Port = $this->config['smtp']['port'];
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = $this->config['smtp']['timeout'] ?? 30;
            $mail->SMTPDebug = 0;

            // From
            $fromAddress = $this->config['from']['address'];

            // Quitar colillas "" de $fromName
            // $fromName = $this->config['from']['name'];
            $fromName = str_replace(['"', '"'], '', $fromName);

            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            file_log('auth', 'Email enviado correctamente', [
                'to' => $toEmail,
                'subject' => $subject
            ]);
            return true;
        } catch (Exception $e) {
            file_log('error', 'Error enviando email: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage(), [
                'to' => $toEmail,
                'subject' => $subject
            ]);
            return false;
        }
    }

    /**
     * Renderiza una plantilla HTML reemplazando variables {{VAR}}
     */
    protected function renderTemplate($filePath, array $vars = []) {
        if (!file_exists($filePath)) {
            return '';
        }
        $template = file_get_contents($filePath);
        foreach ($vars as $key => $value) {
            $template = str_replace('{{' . $key . '}}', htmlspecialchars($value), $template);
        }
        return $template;
    }
}
