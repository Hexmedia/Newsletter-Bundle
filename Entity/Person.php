<?php

namespace Hexmedia\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="newsletter_persons")
 * @ORM\Entity(repositoryClass="Hexmedia\NewsletterBundle\Repository\Doctrine\PersonRepository")
 */
class Person
{
//    use ORMBehaviors\Blameable\Blameable,
//        ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var string
     * @ORM\Column(name="name", type="datetime", nullable=true)
     */
    private $lastSend;

    /**
     * @var \Hexmedia\NewsletterBundle\Entity\Mail
     *
     * @ORM\ManyToMany(targetEntity="Hexmedia\NewsletterBundle\Entity\Mail", inversedBy="persons")
     * @ORM\JoinTable(name="newsletter_sent_to",
     *        joinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")},
     *        inverseJoinColumns={@ORM\JoinColumn(name="mail_id", referencedColumnName="id")}
     *    )
     */
    private $mails;

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
     * Set email
     *
     * @param string $email
     * @return newsletter
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set lastSend
     *
     * @param mixed $lastSend
     * @return newsletter
     */
    public function setLastSend($lastSend)
    {
        $this->lastSend = $lastSend;

        return $this;
    }

    /**
     * Get lastSend
     *
     * @return string
     */
    public function getLastSend()
    {
        return $this->lastSend;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mails = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add mails
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Mail $mails
     * @return Newsletter
     */
    public function addMail(\Hexmedia\NewsletterBundle\Entity\Mail $mails)
    {
        $this->mails[] = $mails;
    
        return $this;
    }

    /**
     * Remove mails
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Mail $mails
     */
    public function removeMail(\Hexmedia\NewsletterBundle\Entity\Mail $mails)
    {
        $this->mails->removeElement($mails);
    }

    /**
     * Get mails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMails()
    {
        return $this->mails;
    }
}