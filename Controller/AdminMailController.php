<?php

namespace Hexmedia\NewsletterBundle\Controller;

use Hexmedia\AdministratorBundle\Controller\CrudController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hexmedia\NewsletterBundle\Entity\Person;
use Hexmedia\NewsletterBundle\Repository\Doctrine\MailRepository;
use Hexmedia\NewsletterBundle\Entity\Mail;
use Hexmedia\NewsletterBundle\Form\Type\Mail\AddType;
use Hexmedia\NewsletterBundle\Form\Type\Mail\EditType;
use Symfony\Component\HttpFoundation\Request;

class AdminMailController extends Controller
{
    /**
     * @return AddType
     */
    protected function getAddFormType()
    {
        return new AddType();
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return "HexMediaNewsletterMail";
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return "mail";
    }

    /**
     * @return string
     */
    protected function getListTemplate()
    {
        return "HexmediaNewsletterBundle:AdminMail";
    }

    /**
     * Registering BreadCrumbs
     *
     * @return \WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs
     */
    protected function registerBreadcrubms()
    {
        $this->breadcrumbs = $this->get("white_october_breadcrumbs");

        $this->breadcrumbs->addItem(
            $this->get('translator')->trans("Newsletter"),
            $this->get("router")->generate("HexMediaNewsletterDashboard")
        );

        $this->breadcrumbs->addItem(
            $this->get('translator')->trans("Mail"),
            $this->get('router')->generate('HexMediaNewsletterMail')
        );

        return $this->breadcrumbs;
    }

    /**
     * @return Mail
     */
    protected function getNewEntity()
    {
        return new Mail();
    }

    /**
     * @return MailRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('HexmediaNewsletterBundle:Mail');
    }

    /**
     * @return EditType
     */
    protected function getEditFormType()
    {
        return new EditType();
    }

    /**
     * {@inheritdoc}
     *
     * @Rest\View(template="HexmediaNewsletterBundle:AdminMail:add.html.twig")
     */
    public function createAction(Request $request)
    {
        return parent::createAction($request);
    }

    /**
     * {@inheritdoc}
     *
     * @Rest\View(template="HexmediaNewsletterBundle:AdminMail:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return parent::updateAction($request, $id);
    }

    public function sendAction($id) {
        $mailRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Mail");
        $personRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Person");

        $mail = $mailRepo->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        if ($mail instanceof Mail);

        $persons = $personRepo->findAll();

        $mailContent = $mail->getContent();

        foreach ($persons as $person) {
            if ($person instanceof Person)
            $message = \Swift_Message::newInstance()
                ->setSubject($mail->getTitle())
                ->setFrom($this->container->getParameter("newsletter_from"), $this->container->getParameter("newsletter_formname"))
                ->setTo($person->getEmail())
                ->setBody(
                    $this->renderView(
                        $this->container->getParameter("newsletter_template"),
                        array('content' => $mail->getContent())
                    )
                )
            ;

            $mail->addPerson($person);

            $this->get('mailer')->send($message);
        }

        $mail->setSent(new \DateTime());

        $entityManager->persist($mail);
        $entityManager->flush();

        return $this->redirect($this->get("router")->generate("HexMediaNewsletterMail"));
    }
}
