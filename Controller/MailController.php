<?php

namespace Hexmedia\NewsletterBundle\Controller;

use Hexmedia\NewsletterBundle\Form\Type\SignInType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Hexmedia\NewsletterBundle\Entity\Person;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mail controller.
 */
class MailController extends Controller
{
    public function statAction($id)
    {
        $repository = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:SentTo");

        $sendTo = $repository->find($id);

        if ($sendTo->getDateOpen() == null) {
            $sendTo->setDateOpen(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($sendTo);
            $entityManager->flush();
        }

        $decoded = base64_decode("R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");

        $now = new \DateTime(null, new \DateTimeZone('Europe/London'));

        $headers = [
            'Accept-Ranges'    => 'bytes',
            'Content-Type'     => 'image/gif',
            'Content-Length'   => strlen($decoded),
            'Last-Modified'    => $now->format("D, d M y H:i:s T")
        ];


        $response = new Response($decoded, 200, $headers);

        return $response;
    }
}

