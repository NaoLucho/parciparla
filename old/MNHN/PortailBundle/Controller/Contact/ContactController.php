<?php

namespace SiteBundle\Controller\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function contactAction(Request $request)
    {

        $data = $request->request->all();

        // Send mail
        if ($this->sendEmail($data)) {

            // Everything OK, redirect to wherever you want ! :

            return $this->render('MNHNPortailBundle:Contact:success.html.twig');
        } else {
            // An error ocurred, handle
            var_dump("Errooooor :(");
        }
        
    }

    private function sendEmail($data)
    {
        $myappContactMail = $this->container->getParameter('mailer_user');
        $myappContactPassword = $this->container->getParameter('mailer_password');
        
        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_smtp'), 465, 'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("Message de contact OPEN de " . $data["name"] . ": " . $data["objet"])
            ->setFrom($data['email'])
            ->setTo($myappContactMail)
            ->setContentType("text/html")
            ->setBody($data["message"] . '<br><br> De : ' . $data["name"] . ' (' . $data['email'] . ')');

        return $mailer->send($message);
    }
}