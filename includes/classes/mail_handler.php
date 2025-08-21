<?php
/**
 * Class mail_handler
 * for PHPMailer v1.9.9
 *
 * Maurice Mol
 * mauricemol@hotmail.nl
 *
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mail_handler
{
    private $mail;

    public function __construct() {
        global $core;
        $this->mail = new PHPMailer(true);

        // Server settings
        try {
            $this->mail->SMTPDebug = MAIL_DEBUG;                        // Enable verbose debug output
            if (MAIL_IS_SMTP) {$this->mail->isSMTP();};                 // Set mailer to use SMTP
            $this->mail->Host = MAIL_HOST;                              // Specify main and backup SMTP servers
            $this->mail->SMTPAuth = MAIL_SMTP_AUTH;                     // Enable SMTP authentication
            $this->mail->Username = MAIL_USERNAME;                      // SMTP username
            $this->mail->Password = MAIL_PASSWORD;                      // SMTP password
            $this->mail->SMTPSecure = MAIL_SMTPSECURE;                  // Enable TLS encryption, `ssl` also accepted
            $this->mail->Port = MAIL_PORT;                              // TCP port to connect to
        }
        catch (Exception $e)
        {
            $core->er_log("Server settings could not be set. Mailer Error: {$e->errorMessage()}");
        }
    }

    /**
     * set_recipients()
     *
     * @param string $sender
     * @param string $sender_name
     * @param string $target
     * @param string $target_name
     *
     * @return boolean
     */
    public function set_recipients($sender, $sender_name, $target, $target_name) {
        global $core;

        try
        {
            $this->mail->setFrom($sender, $sender_name);
            $this->mail->addAddress($target, $target_name);
        }
        catch (Exception $e)
        {
            $core->er_log("Recipients could not be set. Mailer Error: {$e->errorMessage()}");
            return false;
        }
        return true;
    }
	
	/**
	 * add_cc()
	 *
	 * $param string $email
	 *
	 * @return boolean
	 */
	 public function add_cc($mail) {
	     global $core;

		 try
		 {
			 $this->mail->AddCC($mail);
		 }
		 catch (Exception $e)
		 {
			 $core->er_log("CC could not be set. Mailer Error: {$e->errorMessage()}");
			 return false;
		 }
		 return true;
	 }
	 
	 /**
	 * add_bcc()
	 *
	 * $param string $email
	 *
	 * @return boolean
	 */
	 public function add_bcc($mail) {
	     global $core;

		 try
		 {
			 $this->mail->addBCC($mail);
		 }
		 catch (Exception $e)
		 {
			 $core->er_log("BCC could not be set. Mailer Error: {$e->errorMessage()}");
			 return false;
		 }
		 return true;
	 }

    /**
     * add_attachment()
     *
     * @param string $path
     * @param string $name
     *
     * @return boolean
     */
    public function add_attachment($path, $name) {
        global $core;

        try
        {
            $this->mail->addAttachment($path, $name);               // Add attachments
        }
        catch (Exception $e)
        {
            $core->er_log("Failed to add attachment. Mailer Error: {$e->errorMessage()}");
            return false;
        }
        return true;
    }

    /**
     * send()
     *
     * @param string $subject
     * @param string $body
     *
     * @return boolean
     */
    public function send($subject, $body) {
        global $core;

        try
        {
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            if ($this->mail->send()) {
                $this->save_mail($this->mail);
            }
        }
        catch (Exception $e)
        {
            $core->er_log("Failed to send email. Mailer Error: {$e->errorMessage()}");
            return false;
        }
        return true;
    }

    /**
     * save_mail()
     *
     * @param $mail
     */
    function save_mail($mail)
    {
        global $core;

        $path = '{localhost:993/imap/ssl/novalidate-cert}Webshop';

        try {
            $imapStream = imap_open($path, MAIL_IMAP_USER, MAIL_IMAP_PASS);

            imap_append($imapStream, $path, $mail->getSentMIMEMessage());

            imap_close($imapStream);
        }
        catch (Exception $e)
        {
            $core->er_log("Failed to send email. Mailer Error: {$e->errorMessage()}");
        }
    }
}