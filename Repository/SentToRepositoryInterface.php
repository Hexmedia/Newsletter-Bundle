<?php

namespace Hexmedia\NewsletterBundle\Repository;

interface SentToRepositoryInterface {
    public function getMailsToSent($limit = 100);
} 