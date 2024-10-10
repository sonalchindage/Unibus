<?php
require 'PHPMailer/PHPMailerAutoload.php';

function attchMailNew($recipientName, $recipientEmail, $subject, $body, $altBody, $cc = null, $bcc = null, $attachment = null) {
    $mail = new PHPMailer();

    try {
        // Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'prajktajagtap018@gmail.com';       // SMTP username
        $mail->Password = 'zeqjyzgbvlxuvpzf';              // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption
        $mail->Port = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('prajktajagtap018@gmail.com', 'Stay Easy admin');   // Set the sender's email and name
        $mail->addAddress($recipientEmail, $recipientName);    // Add a recipient

        if ($cc) {
            $mail->addCC($cc);                                // Add CC recipient
        }

        if ($bcc) {
            $mail->addBCC($bcc);                              // Add BCC recipient
        }

        // Attachments
        if ($attachment && isset($attachment['tmp_name']) && $attachment['tmp_name'] != '') {
            $mail->addAttachment($attachment['tmp_name'], $attachment['name']); // Add attachment
        }

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody;

        // Send email and return true for success
        if ($mail->send()) {
            return [
                'status' => true,
                'message' => "Email Send Sccessfuly"
            ];
        } else {
            // Return false for failure and include the error message
            return [
                'status' => false,
                'message' => 'Mailer Error: ' . $mail->ErrorInfo
            ];
        }

    } catch (phpmailerException $e) {  // Catch PHPMailer's own exceptions
        return [
            'status' => false,
            'message' => 'PHPMailer Error: ' . $e->getMessage()
        ];
    } catch (Exception $e) {  // Catch general exceptions
        return [
            'status' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

function attchMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc = null, $bcc = null, $attachment = null) {
    $mail = new PHPMailer;

    // $mail->SMTPDebug = 3;  // Enable verbose debug output (Uncomment this for debugging)

    $mail->isSMTP();                                       // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                // Enable SMTP authentication
    $mail->Username = 'prajktajagtap018@gmail.com';       // SMTP username
    $mail->Password = '
    ';                  // SMTP password
    $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, ssl also accepted
    $mail->Port = 587;                                     // TCP port to connect to

    $mail->setFrom('prajktajagtap018@gmail.com', 'Stay Easy admin');   // Set the sender's email and name
    $mail->addAddress($recipientEmail, $recipientName);    // Add a recipient

    if ($cc) {
        $mail->addCC($cc);                                  // Add CC recipient
    }
    
    if ($bcc) {
        $mail->addBCC($bcc);                                // Add BCC recipient
    }

    // Check if there is an attachment
    if ($attachment && isset($attachment['tmp_name']) && $attachment['tmp_name'] != '') {
        $mail->addAttachment($attachment['tmp_name'], $attachment['name']); // Add attachment
    }
    
    $mail->isHTML(true);                                    // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $altBody;

    if (!$mail->send()) {
        return 'Mailer Error: ' . $mail->ErrorInfo;         // Return error message if the email fails to send
    } else {
        $msg = "We've just sent a verification link to $recipientEmail. 
                    Please check your inbox and click on the link to get started. 
                    If you can't find the email (which could be due to a spam filter), 
                    just request a new one here.";
        return $msg;                                        // Return success message if the email is sent
    }
}

function newMail($recipientName, $recipientEmail, $subject, $body, $altBody, $cc, $bcc) {
    $mail = new PHPMailer;

    // $mail->SMTPDebug = 3;  // Enable verbose debug output (Uncomment this for debugging)

    $mail->isSMTP();                                       // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                // Enable SMTP authentication
    $mail->Username = 'prajktajagtap018@gmail.com';              // SMTP username
    $mail->Password = 'zeqjyzgbvlxuvpzf';                 // SMTP password
    $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                     // TCP port to connect to

    $mail->setFrom('prajktajagtap018@gmail.com', 'Stay Easy admin');   // Set the sender's email and name
    $mail->addAddress($recipientEmail, $recipientName);    // Add a recipient
    //$mail->addAddress('ellen@example.com');              // Name is optional

    // if($addReplyTo){
    // $mail->addReplyTo('info@example.com', 'Information'); // Reply-to address (optional)
    // }

    if ($cc) {
        $mail->addCC($cc);                                  // Add CC recipient
    }
    
    if ($bcc) {
        $mail->addBCC($bcc);                                // Add BCC recipient
    }

    //$mail->addAttachment('/var/tmp/file.tar.gz');          // Add attachments (optional)

    // if($addAttachment){
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');     // Optional name (optional)
    // }
    
    $mail->isHTML(true);                                    // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $altBody;

    // We've just sent a verification link to <strong class='text-success'>$recipientEmail</strong>. Please check your inbox.
    if (!$mail->send()) {
        return false;          // Return false if the email fails to send
        //return 'Mailer Error: ' . $mail->ErrorInfo;         // Return false if the email fails to send
    } else {
        return true;                                        // Return true if the email is successfully sent
    }
}

function newMailOld($recipientName, $recipientEmail, $cc, $bcc, $subject, $body, $altBody){
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'prajktajagtap018@gmail.com';                 // SMTP username
    $mail->Password = 'zeqjyzgbvlxuvpzf';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('prajktajagtap018@gmail.com', 'Stay Easy admin');
    $mail->addAddress($recipientEmail, $recipientName);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC($cc);
    $mail->addBCC($bcc);

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $subject;//'Here is the subject';
    $mail->Body    = $body;// 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = $altBody;//'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        return false;
        // echo 'Message could not be sent.';
        // echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        // echo 'Message has been sent';
        return true;
    }
}
?>