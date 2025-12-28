<?php
// controllers/RecuperarController.php 
include_once '../model/user.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require_once "../config/connection.php";
if (isset($_GET['action'])) {
    
    $user = new User();
    
    if ($_GET['action'] == 'solicitar') {
    
        $correo = $_POST['correo'];
        if ($user->existCorreo($correo)) {
            $token = bin2hex(random_bytes(10));  // Generar token
            $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
    
            // Guardar el token en la base de datos
            $user->guardarToken($correo, $token, $expira);
            
            // Crear el enlace de recuperación con el token como parámetro
            $url = "http://localhost/restaurante/view/RestablecerContrasena.php ? token = $token";
            
            $mensaje = "Hola,\n\nPor favor, restablece tu contraseña haciendo clic en el siguiente enlace:\n\n$url\n\nSi no solicitaste este cambio, ignora este mensaje.";

            // Configurar PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail para pruebas
                $mail->SMTPAuth = true;
                $mail->Username = 'fm1250929@gmail.com';  // Dirección de correo Gmail
                $mail->Password = 'kyus gixb jyqx qnsi';  // Contraseña o clave de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('fm1250929@gmail.com', 'RESTAURANTE');
                $mail->addAddress($correo);  // Destinatario
                $mail->isHTML(false);
                $mail->Subject = 'Restablecimiento de Contrasena';
                $mail->Body    = $mensaje;

                // Enviar el correo
                $mail->send();
                echo 'Se ha enviado un correo de recuperación.';
                

            } catch (Exception $e) {
                echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Correo no registrado.";    
        }
    }

    if ($_GET['action'] == 'restablecer') {
        $token = $_POST['token'];
        $nuevaContrasena = $_POST['nueva_contrasena'];
        if ($user->verificarToken($token)) {
            $user->actualizarContrasenaConToken($token, $nuevaContrasena);
            
            echo "Contraseña actualizada correctamente.";
            
        } else {
            echo "El enlace es inválido o ha expirado.";
        }
    }
    if ($_GET['action'] == 'solicitarToken') {
    
        $correo = $_POST['correo'];
        if ($user->existCorreo($correo)) {
            $token = bin2hex(random_bytes(10));  // Generar token
            $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));
    
            // Guardar el token en la base de datos
            $user->guardarToken($correo, $token, $expira);
            
            // Crear el enlace de recuperación con el token como parámetro
            $url = "http://localhost/restaurante/view/RestablecerContrasena.php ? token = $token";
            
            $mensaje = "Hola,este es un mensaje de solicitud de recuperacion administrativa , restablece contraseña haciendo clic en el siguiente enlace:\n\n$url\n\nSi no se solicito este cambio, ignora este mensaje.";

            // Configurar PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail para pruebas
                $mail->SMTPAuth = true;
                $mail->Username = 'fm1250929@gmail.com';  // Dirección de correo Gmail
                $mail->Password = 'kyus gixb jyqx qnsi';  // Contraseña o clave de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configuración del correo
                $mail->setFrom('fm1250929@gmail.com', 'RESTAURANTE');
                $mail->addAddress('fm1250929@gmail.com');  // Destinatario correo administrativo
                $mail->isHTML(false);
                $mail->Subject = 'Restablecimiento de Contrasena';
                $mail->Body    = $mensaje;

                // Enviar el correo
                $mail->send();
                echo 'Se ha enviado un token de recuperación al correo administrativo.';
                
               
            } catch (Exception $e) {
                echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Correo no registrado.";
        }
    }
}
