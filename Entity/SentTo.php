<?php

namespace Hexmedia\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SentTo
 *
 * @ORM\Table(name="newsletter_sent_to", uniqueConstraints={@ORM\UniqueConstraint(columns={"mail_id", "person_id"})})
 * @ORM\Entity(repositoryClass="Hexmedia\NewsletterBundle\Repository\Doctrine\SentToRepository")
 */
class SentTo
{
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sent", type="datetime", nullable=true)
     */
    private $dateSent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_open", type="datetime", nullable=true)
     */
    private $dateOpen;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_html", type="text", nullable=true)
     */
    private $mailHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_title", type="string", length=255, nullable=true)
     */
    private $mailTitle;

    /**
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="Hexmedia\NewsletterBundle\Entity\Mail", inversedBy="sentTo")
     */
    private $mail;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="Hexmedia\NewsletterBundle\Entity\Person", inversedBy="sentTo")
     */
    private $person;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateSent
     *
     * @param \DateTime $dateSent
     * @return SentTo
     */
    public function setDateSent($dateSent)
    {
        $this->dateSent = $dateSent;
    
        return $this;
    }

    /**
     * Get dateSent
     *
     * @return \DateTime 
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }

    /**
     * Set dateOpen
     *
     * @param \DateTime $dateOpen
     * @return SentTo
     */
    public function setDateOpen($dateOpen)
    {
        $this->dateOpen = $dateOpen;
    
        return $this;
    }

    /**
     * Get dateOpen
     *
     * @return \DateTime 
     */
    public function getDateOpen()
    {
        return $this->dateOpen;
    }

    /**
     * Set mailHtml
     *
     * @param string $mailHtml
     * @return SentTo
     */
    public function setMailHtml($mailHtml)
    {
        $this->mailHtml = $mailHtml;
    
        return $this;
    }

    /**
     * Get mailHtml
     *
     * @return string 
     */
    public function getMailHtml()
    {
        return $this->mailHtml;
    }

    /**
     * Set mailTitle
     *
     * @param string $mailTitle
     * @return SentTo
     */
    public function setMailTitle($mailTitle)
    {
        $this->mailTitle = $mailTitle;
    
        return $this;
    }

    /**
     * Get mailTitle
     *
     * @return string 
     */
    public function getMailTitle()
    {
        return $this->mailTitle;
    }

    /**
     * Set mail
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Mail $mail
     * @return SentTo
     */
    public function setMail(\Hexmedia\NewsletterBundle\Entity\Mail $mail = null)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return \Hexmedia\NewsletterBundle\Entity\Mail 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set person
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Person $person
     * @return SentTo
     */
    public function setPerson(\Hexmedia\NewsletterBundle\Entity\Person $person = null)
    {
        $this->person = $person;
    
        return $this;
    }

    /**
     * Get person
     *
     * @return \Hexmedia\NewsletterBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}