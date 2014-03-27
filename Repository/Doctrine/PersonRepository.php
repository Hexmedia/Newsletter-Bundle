<?php

namespace Hexmedia\NewsletterBundle\Repository\Doctrine;

use Doctrine\ORM\EntityRepository;
use Hexmedia\AdministratorBundle\Repository\Doctrine\ListTrait;
use Hexmedia\NewsletterBundle\Entity\Mail;
use Hexmedia\NewsletterBundle\Repository\PersonRepositoryInterface;

/**
 * newsletterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends EntityRepository implements PersonRepositoryInterface
{
    use ListTrait;

    public function getPeopleToSent(Mail $mail) {
        $queryBuilder = $this->createQueryBuilder("p");

        $queryBuilder->leftJoin('p.sentTo', 'st', 'WITH', 'st.mail=:mailId');
        $queryBuilder->where(
            $queryBuilder->expr()->isNull("st.id")
        );
        $queryBuilder->setParameter(":mailId", $mail->getId());

        return $queryBuilder->getQuery()->getResult();
    }
}
