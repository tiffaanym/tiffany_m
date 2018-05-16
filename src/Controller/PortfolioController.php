<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 */

namespace Controller;

use Model\PortfolioManager;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class PortfolioController
 * @package Controller
 */
class PortfolioController extends AbstractController
{

    public function index()
    {
        $PortfolioManager = new PortfolioManager();
        $items = $PortfolioManager->findAll();

        return $this->twig->render('layout.html.twig', ['items' => $items]);

    }


    public function sendMail()
    {
        $error = [];
        $mail = new PHPMailer(true);
        // Passing `true` enables exceptions

        if (isset($_POST['submit']) && !empty($_POST['nomMail'])) {

            try {
                //Server settings
                $mail->CharSet = 'UTF-8';
                $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                $mail->isSMTP();                                                // Set mailer to use SMTP
                $mail->Host = 'smtp.orange.fr';                                 // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                $mail->Username = 'tiffany.maurath@orange.fr';                  // SMTP username
                $mail->Password = 'devine';                                    // SMTP password
                $mail->SMTPSecure = 'ssl';                                      // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                              // TCP port to connect to

                //Recipients
                $mail->setFrom('tiffany.maurath@orange.fr', 'Mon portfolio');
                $mail->addAddress('tiffany.maurath@orange.fr', 'Moi');     // Add a recipient

                //Content
                $mail->isHTML(true);                                      // Set email format to HTML
                $mail->Subject = 'Message de ' . $_POST['nomMail'];
                $mail->Body = 'Message utilisateur : ' . $_POST['messageMail'];
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send(header('location:/'));
                $error = "Message envoyé";
            } catch (Exception $e) {
                $error = "Le message n'a pas pu être envoyé";
                $mail->ErrorInfo;
            }

            return $this->twig->render('layout.html.twig', ['error' => $error]);
        }


    }


}
