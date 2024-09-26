<?php

session_start();

// Incluye los archivos de PHPMailer manualmente
require 'C:/xampp/htdocs/INF281/PHPMailer/src/Exception.php';
require 'C:/xampp/htdocs/INF281/PHPMailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/INF281/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "conexion.php";

$nom = $_POST['nombre'];
//$pat = $_POST['ap_paterno'];
//$mat = $_POST['ap_materno'];
$ci = $_POST['ci'];
//$sex = $_POST['sexo'];
$tel = $_POST['telefono'];
$cor = $_POST['email'];
$usu = $_POST['usuario'];
$pas = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$typeUsu = $_POST['Tipo_Usu'];
//$foto = $_FILES['foto']['name'];
//$temporal = $_FILES['foto']['tmp_name'];
$carpetaruta = '../assets/img/participantes';
$carpeta = __DIR__ . '/../assets/img/participantes';
//$ruta = $carpetaruta . '/' . $foto;
//move_uploaded_file($temporal, $carpeta . '/' . $foto);

if ($pas !== $confirm_password) {
    echo "<script>alert('Las contraseñas no coinciden.'); window.location.href='formulariousu.php';</script>";
    exit;
}
if (!preg_match('/[!@#$%^&*]/', $pas)) {
    echo "<script>alert('La contraseña debe tener al menos un símbolo (!@#$%^&*).'); window.location.href='formulariousu.php';</script>";
    exit;
}

$contrasena_encriptada = sha1($pas);
$consulta = "INSERT INTO usuario (tipo_idtipo, nombre, ci, telefono, email,  usuario, contraseña)  
             VALUES ($typeUsu, '$nom', '$ci', '$tel', '$cor', '$usu', '$contrasena_encriptada')";
$res = mysqli_query($mysqli, $consulta);

/*$consulta = "INSERT INTO usuario (nombre, email, contraseña,  ci, telefono, usuario, tipo_idtipo)  
             VALUES ('$nom', '$cor','$contrasena_encriptada','$ci', '$tel',  '$usu', $typeUsu')";
             var_dump($nom, $cor, $contrasena_encriptada, $ci, $tel, $usu, $typeUsu);

$res = mysqli_query($mysqli, $consulta);
*/
$numeroAleatorio = rand(100000, 999999);
$_SESSION['numAleatorio'] = $numeroAleatorio;

if ($res) {
    // Envío de correo usando PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'ltorrezq2@fcpn.edu.bo'; // Tu correo de Gmail
        $mail->Password = 'tejn povp rblw xntm'; // Contraseña de aplicación (no la contraseña regular)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinatarios
        $mail->setFrom('your-email@gmail.com', 'Your Name'); // Reemplaza con tu correo y nombre
        $mail->addAddress($cor);  // Dirección del destinatario

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de registro';
        $mail->Body = 'Gracias por registrarte! Tu número de inicio de sesión es: ' . $numeroAleatorio;

        $mail->send();
        echo "<script>
            alert('Correo de inicio de sesion enviado.');
            window.location.href='validar.php';
        </script>";
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
    }
} else {
    echo "<script>
        alert('Complete las casillas Vacias!');
        window.location.href='formulariousu.php';
    </script>";
}
?>
