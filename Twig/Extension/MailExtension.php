<?php

namespace Hexmedia\NewsletterBundle\Twig\Extension;

use Hexmedia\NewsletterBundle\Templating\Helper\MailHelper;

/**
 * Time formatter extension.
 */
class MailExtension extends \Twig_Extension
{

	/**
	 * Mail Template Helper
	 *
	 * @var MailHelper
	 */
	protected $helper;


    /**
     * @param MailHelper $helper
     */
    public function __construct(MailHelper $helper)
	{
		$this->helper = $helper;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('mail_embed', array($this, 'embed')),
		);
	}


    /**
     * Embed image and add it as attachement
     *
     * @param \Swift_Message $message
     * @param $path
     * @param bool $toEmail
     * @param bool $fullUrl
     * @return mixed
     */
    public function embed($path, \Swift_Message $message = null, $toEmail = null, $fullUrl = false)
	{
		return $this->helper->embed($path, $message, $toEmail, $fullUrl);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'mail_embed';
	}

}

