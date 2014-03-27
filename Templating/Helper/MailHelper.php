<?php

namespace Hexmedia\NewsletterBundle\Templating\Helper;

use Hexmedia\NewsletterBundle\Entity\Mail;
use Symfony\Component\DependencyInjection\IntrospectableContainerInterface;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use \Symfony\Component\Translation\TranslatorInterface;

/**
 * MailHelper
 */
class MailHelper extends Helper
{
    /**
     * @var bool
     */
    private $embed = true;

    /**
     * @var \Swift_Message
     */
    private $message;

    /**
     * @var \Symfony\Component\DependencyInjection\IntrospectableContainerInterface
     */
    private $serviceContainer;

    /**
     * Constructor
     */
    public function __construct(IntrospectableContainerInterface $serviceContainer, $embed = true, \Swift_Message $message = null)
    {
        $this->serviceContainer = $serviceContainer;
        $this->embed = $embed;
        $this->message = $message;

    }

    /**
     * @param bool $embed
     * @return $this
     */
    public function setEmbed($embed)
    {
        $this->embed = $embed;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEmbed()
    {
        return $this->embed;
    }

    /**
     * @param \Swift_Message $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return \Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'mail_embed';
    }

    /**
     * Embed image and add it as attachement
     *
     * @param \Swift_Message $message
     * @param $path
     * @param bool $toMail
     * @param bool $fullUrl
     * @return string
     */
    public function embed($path, \Swift_Message $message = null, $toMail = null, $fullUrl = false)
    {

        if ($fullUrl == false) {
            $url = $this->serviceContainer->getParameter("newsletter_base") . $path;
        } else {
            $url = $path;
        }

        if (!$message) {
            $message = $this->getMessage();
        }

        if ($message instanceof \Swift_Message && (
                $toMail == true || ($toMail == null && $this->embed)
            )
        ) {
            return $message->embed(\Swift_Image::fromPath($url));
        } else {
            return $url;
        }
    }

    public function stat($mailId)
    {
        $env = $this->serviceContainer->get("kernel")->getEnvironment();
        $file = "app_$env.php";

        $url = $this->serviceContainer->getParameter("newsletter_base") . $file . $this->serviceContainer->get("router")->generate("_hexmedia_newsletter_stat", ['id' => $mailId]);

        return $url;
    }
}

