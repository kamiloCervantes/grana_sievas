<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require LIBS_PATH.'/PHPMailer/PHPMailerAutoload.php';
/**
 * Description of MailHelper
 *
 * @author Usuario
 */
class MailHelper {
    //put your code here
    private $admin_group = array(
        'cacesa8931@gmail.com',
        'donato.vallin@yahoo.com.mx',
        'dvallin@oui-iohe.org'
    );
    
    public static function mail($to, $message, $subject){
        $admin_group = array(
            'cacesa8931@gmail.com',
            'donato.vallin@yahoo.com.mx',
            'dvallin@oui-iohe.org'
        );
        $mail = new PHPMailer;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'p3plcpnl0676.prod.phx3.secureserver.net ';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@certification-grana.org';                 // SMTP username
        $mail->Password = 'danger89312011';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->isHTML(true); 
        $mail->From = 'noreply@certification-grana.org';
        $mail->FromName = 'NoReply';
        if($to === 'admin_group'){
            foreach($admin_group as $adm){
                $mail->addAddress($adm);
            }
        }
        else{
            $mail->addAddress($to);  
        }

        $mail->Subject = $subject;
        $mail->Body = $message;
        return $mail->send();    
    }
    
    public function test(){
        $mail = new PHPMailer;

//        $mail->SMTPDebug = 3;                               // Enable verbose debug output
        $message = file_get_contents(LIBS_PATH.'/Mail/templates/basic.html'); 
//        $message = str_replace('%testusername%', $username, $message); 
//        $message = str_replace('%testpassword%', $password, $message); 
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'p3plcpnl0676.prod.phx3.secureserver.net ';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@certification-grana.org';                 // SMTP username
        $mail->Password = 'danger89312011';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->From = 'noreply@certification-grana.org';
        $mail->FromName = 'NoReply';
        $mail->addAddress('cacesa8931@gmail.com', 'Camilo Cervantes');     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->msgHTML($message);

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}
