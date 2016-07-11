<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/11/16
 * Time: 2:02 PM
 */
namespace Fab\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_RfcComplianceException;
use Swift_SmtpTransport;

class SwiftMailer
{
    public function __construct()
    {
    }

    public function sendEmail($data)
    {
        $result = array();

        //Check if $_POST is empty and sanitize
        $name = $data['name'];
        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];

        if(!empty($name) && !empty($email) && !empty($message) && !empty($subject)) {
            $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cleanMessage = filter_var($message, FILTER_SANITIZE_STRING);
            $cleanSubject = filter_var($subject, FILTER_SANITIZE_STRING);
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: You need to fill in all the fields.";

            return $result;
        }

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
			->setUsername('spiridakis.grg@gmail.com')
			->setPassword('apofa5555sistikotita')
        ;

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        try {
            // Create the message
            $message = Swift_Message::newInstance()
                // Give the message a subject
                ->setSubject('fabgraphics.gr: ' . $cleanSubject)
                // Set the From address with an associative array
                ->setFrom(array($cleanEmail => $cleanName))
                // Set the To addresses with an associative array
                ->setTo(array('spiridakis.grg@gmail.com' => 'Fab Support Team'))
                // Give it a body
                ->setBody("Sender's Email: " . $cleanEmail . "\n\n" . $cleanMessage);

            // Optionally add any attachments
//			->attach(Swift_Attachment::fromPath('my-document.pdf'))

            // Send the message
            /**
             * @var \Swift_Mime_Message $message
             */
            $messageSent = $mailer->send($message);

        } catch (Swift_RfcComplianceException $e ) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();

            return $result;
        }

        //Confirm Email Sent
        if ($messageSent > 0){
            $result['success'] = 1;
            $result['message'] = "Thank you for your email.\n We'll be in touch soon.";

            return $result;
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: We couldn't send your email. \n Please contact us at 'fab.agia@gmail.com'.";

            return $result;
        }
    }

    public function sendEmailToSupport($data)
    {
        $result = array();

        //Check if $_POST is empty and sanitize
        $mobile = $data['mobile'];
        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];

        if(!empty($mobile) && !empty($email) && !empty($message) && !empty($subject)) {
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cleanMessage = filter_var($message, FILTER_SANITIZE_STRING);
            $cleanSubject = filter_var($subject, FILTER_SANITIZE_STRING);
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: You need to fill in all the fields.";

            return $result;
        }

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.secureserver.net.', 25)
//			->setUsername('your username')
//			->setPassword('your password')
        ;

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        try {
            // Create the message
            $message = Swift_Message::newInstance()
                // Give the message a subject
                ->setSubject('Email from fabgraphics.gr: ' . $cleanSubject)
                // Set the From address with an associative array
                ->setFrom(array($cleanEmail => 'Nikos Davrazos'))
                // Set the To addresses with an associative array
                ->setTo(array('support@codeburrow.com' => 'CodeBurrow Support Team'))
                // Give it a body
                ->setBody($cleanMessage . "\n Mobile Number: " . $mobile);

            // Optionally add any attachments
//			->attach(Swift_Attachment::fromPath('my-document.pdf'))

            // Send the message
            /**
             * @var \Swift_Mime_Message $message
             */
            $messageSent = $mailer->send($message);

        } catch (Swift_RfcComplianceException $e ) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();

            return $result;
        }

        //Confirm Email Sent
        if ($messageSent > 0){
            $result['success'] = 1;
            $result['message'] = "Thank you for your email.\n We'll be in touch soon.";

            return $result;
        } else {
            $result['success'] = 0;
            $result['message'] = "Error: We couldn't send your email. \n Please contact us at 'support@codeburrow.com'.";

            return $result;
        }
    }


}