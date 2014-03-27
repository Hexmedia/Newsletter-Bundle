<?php

namespace Hexmedia\NewsletterBundle\Service;

use Doctrine\ORM\EntityManager;
use Hexmedia\NewsletterBundle\Entity\Mail;
use Hexmedia\NewsletterBundle\Entity\SentTo;
use Hexmedia\NewsletterBundle\Repository\SentToRepositoryInterface;
use Hexmedia\NewsletterBundle\Templating\Helper\MailHelper;
use Symfony\Bundle\TwigBundle\TwigEngine;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Sender
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $doctrine;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Hexmedia\NewsletterBundle\Templating\Helper\MailHelper
     */
    private $helper;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    private $templating;

    /**
     * @var
     */
    private $container;

    public function __construct(EntityManager $doctrine, \Swift_Mailer $mailer, MailHelper $helper, TwigEngine $templating, $container)
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
        $this->helper = $helper;
        $this->templating = $templating;
        $this->container = $container;
    }


    public function sendMany($limit)
    {
        $repository = $this->doctrine->getRepository("HexmediaNewsletterBundle:SentTo");

        if ($repository instanceof SentToRepositoryInterface) ;

        $mails = $repository->getMailsToSent($limit);

        foreach ($mails as $mail) {
            $this->sendOne($mail);
        }
    }

    public function sendOne(SentTo $mail, $save = true)
    {
        $message = $this->getMessage($mail);

        $failures = [];

        $sent = $this->mailer->send($message, $failures);

        if ($sent) {
            if ($save) {
                $mail->setDateSent(new \DateTime());
                $this->doctrine->persist($mail);
                $this->doctrine->flush();
            }

            return true;
        }

        return false;
    }

    /**
     * @param SentTo $mail
     * @return \Swift_Message
     */
    private function getMessage(SentTo $mail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getMail()->getTitle())
            ->setFrom($this->container->getParameter("newsletter_from"), $this->container->getParameter("newsletter_fromname"))
            ->setTo(trim($mail->getPerson()->getEmail()), trim($mail->getPerson()->getName()))
            ->setCharset("UTF-8")
            ->setContentType("text/html");

        $this->helper->setEmbed(true);
        $this->helper->setMessage($message);

        $body = $this->renderMail($mail, $message);

        $message
            ->setBody($body, 'text/html');

        $mail->setMailHtml($body);
        $mail->setMailTitle($mail->getMail()->getTitle());

        $this->helper->setEmbed(false);
        $this->helper->setMessage(null);

        return $message;
    }

    public function renderMail(SentTo $mail, \Swift_Message $message = null)
    {
        $html = $this->templating->render(
            $this->container->getParameter("newsletter_template"),
            [
                'mail' => $mail->getMail(),
                'person' => $mail->getPerson(),
                'sentTo' => $mail,
                'message' => $message
            ]
        );

        $css = $this->templating->render(
            $this->container->getParameter("newsletter_css"),
            []
        );

        $converter = new CssToInlineStyles($html, $css);
        $view = $converter->convert();

        return $view;
    }
} 