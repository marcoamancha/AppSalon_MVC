<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

//Clase para poder enviar el email con el token al correo del usuario
class Email {
    public $nombre;
    public $email;   
    public $token;    
    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;        
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        //Crear el objeto del email -> Se trae desde Mailtrap
        // Crear el objeto de Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = 'e9e694f1a2becd';
        $mail->Password = '134e3cc6583939';
 
        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress($this->email);
        $mail->Subject = "Confirma tu Cuenta";
 
        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";
 
        $contenido = "<html>";
        $contenido.= "<p><strong>Hola " . $this->nombre . "</strong> Has Creado tu Cuenta en App Salon, solo debes confirmala presionando el siguiente enlace</p>";
        $contenido.= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido.= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido.= "</html>";
        $mail->Body = $contenido;
 
        // Enviar el Email
        $mail->send();
    }

     //Enviar instrucciones al correo
    public function enviarInstrucciones(){
         //Crear el objeto del email -> Se trae desde Mailtrap
        // Crear el objeto de Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->Username = 'e9e694f1a2becd';
        $mail->Password = '134e3cc6583939';
 
        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress($this->email);
        $mail->Subject = "Restablecer contraseña";
 
        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";
 
        $contenido = "<html>";
        $contenido.= "<p><strong>Hola " . $this->nombre . "</strong>Haz solicitado cambiar tu contraseña, por favor ingresa al siguiente link:</p>";
        $contenido.= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Cambiar Contraseña</a></p>";
        $contenido.= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido.= "</html>";
        $mail->Body = $contenido;
 
        // Enviar el Email
        $mail->send();
    }
}
