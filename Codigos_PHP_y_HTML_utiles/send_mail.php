<?php
    require ("./PHPMailer/Exception.php");
    require ("./PHPMailer/PHPMailer.php");
    require ("./PHPMailer/SMTP.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function send_mail($para, $usuario, $asunto, $mensaje)
    {
        $objeto_mail = new PHPMailer();
        $objeto_mail->isSMTP();
        $objeto_mail->Host="smtp.gmail.com";
        $objeto_mail->Port=587;
        $objeto_mail->SMTPSecure="tls";
        $objeto_mail->SMTPAuth=true;
        $objeto_mail->Username="santiyfer21@gmail.com";
        $objeto_mail->Password="v2FX4k0xD1sj26d9";
        $objeto_mail->setFrom("santiyfer21@gmail.com", "R.S.A");
        $objeto_mail->addAddress($para, $usuario);
        $objeto_mail->Subject=$asunto;
        $objeto_mail->msgHTML($mensaje);
    
        if(!$objeto_mail->send())
        {
            return false;
        }
        else
        {
            return true;
        }
        
    }


?>