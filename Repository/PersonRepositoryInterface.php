<?php

namespace Hexmedia\NewsletterBundle\Repository;

use Hexmedia\NewsletterBundle\Entity\Mail;

interface PersonRepositoryInterface {
    public function getPeopleToSent(Mail $mail) ;
} 