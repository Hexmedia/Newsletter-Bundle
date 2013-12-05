<?php

namespace Hexmedia\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Mail
 *
 * @ORM\Table("newsletter_mail")
 * @ORM\Entity(repositoryClass="Hexmedia\NewsletterBundle\Repository\Doctrine\MailRepository")
 */
class Mail
{
//    use ORMBehaviors\Blameable\Blameable,
//        ORMBehaviors\Timestampable\Timestampable,
//        ORMBehaviors\Loggable\Loggable;

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent", type="datetime", nullable=true)
     */
    private $sent;

    /**
     * @var \Hexmedia\NewsletterBundle\Entity\Person
     *
     * @ORM\ManyToMany(targetEntity="Hexmedia\NewsletterBundle\Entity\Person", mappedBy="mails")
     */
    private $persons;

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
     * Set title
     *
     * @param string $title
     * @return Mail
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Mail
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set sent
     *
     * @param \DateTime $sent
     * @return Mail
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    
        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime 
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Add persons
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Person $persons
     * @return Mail
     */
    public function addPerson(\Hexmedia\NewsletterBundle\Entity\Person $persons)
    {
        $this->persons[] = $persons;
    
        return $this;
    }

    /**
     * Remove persons
     *
     * @param \Hexmedia\NewsletterBundle\Entity\Person $persons
     */
    public function removePerson(\Hexmedia\NewsletterBundle\Entity\Person $persons)
    {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersons()
    {
        return $this->persons;
    }
}