<?php

namespace Hexmedia\NewsletterBundle\Controller;

use Hexmedia\NewsletterBundle\Form\Type\SignInType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Hexmedia\NewsletterBundle\Entity\Person;
use Doctrine\DBAL\DBALException;

/**
 * Person controller.
 */
class PersonController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Template
     */
    public function signInAction(Request $request)
    {
        $person = new Person();

        $form = $this->createForm(new SignInType(), $person);

        $form->handleRequest($request);

        if (!$person->getName()) {
            $person->setName($person->getEmail());
        }

        $manager = $this->getDoctrine()->getManager();

        try {
            $manager->persist($person);
            $manager->flush();
        } catch (DBALException $exception) {
            $this->forward("HexmediaNewsletterBundle:Person:alreadySigned");
        }

        return $this->forward("HexmediaNewsletterBundle:Person:thankYouSignIn");
    }

    /**
     * @return array
     *
     * @Template
     */
    public function formAction()
    {
        $form = $this->createForm(new SignInType());

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Template
     */
    public function signOutAction(Request $request)
    {
        return [];
    }

    /**
     * @return array
     *
     * @Template
     */
    public function thankYouSignInAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($this->get('translator')->trans("Newsletter"));
        $breadcrumbs->addItem($this->get('translator')->trans("Dziękujemy"));
        return [];
    }

    /**
     * @return array
     *
     * @Template
     */
    public function thankYouSingOutAction()
    {
        return [];
    }
}

