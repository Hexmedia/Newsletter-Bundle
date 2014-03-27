<?php

namespace Hexmedia\NewsletterBundle\Controller;

use CinemaForum\CatalogBundle\Entity\Sponsor;
use Hexmedia\AdministratorBundle\Controller\CrudController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hexmedia\NewsletterBundle\Entity\Person;
use Hexmedia\NewsletterBundle\Entity\SentTo;
use Hexmedia\NewsletterBundle\Repository\Doctrine\MailRepository;
use Hexmedia\NewsletterBundle\Entity\Mail;
use Hexmedia\NewsletterBundle\Form\Type\Mail\AddType;
use Hexmedia\NewsletterBundle\Form\Type\Mail\EditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

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
     * @param int $page
     * @return array|void
     *
     * @Rest\View(template="HexmediaNewsletterBundle:AdminMail:list.html.twig")
     */
    public function listAction($page = 1) {
        return parent::listAction($page);
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

    public function sendAction($id)
    {
        $mailRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Mail");
        $personRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Person");

        $mail = $mailRepo->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        if ($mail instanceof Mail) {

            $persons = $personRepo->getPeopleToSent($mail);

            foreach ($persons as $person) {
                if ($person instanceof Person) {
                    $sentTo = new SentTo();

                    $sentTo->setPerson($person);
                    $sentTo->setMail($mail);

                    $entityManager->persist($sentTo);
                }
            }

            $mail->setSent(new \DateTime());

            $entityManager->persist($mail);

            $entityManager->flush();
        }

        return $this->redirect($this->get("router")->generate("HexMediaNewsletterMail"));
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction(Request $request, $id)
    {
        $mailRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Mail");
        $mail = $mailRepo->find($id);

        $person = new Person();
        $person->setName($request->get("to_name"));
        $person->setEmail($request->get("to"));

        $sentTo = new SentTo();
        $sentTo->setMail($mail);
        $sentTo->setPerson($person);

        $this->get("hexmedia.templating.helper.mail_embed")->setEmbed(false);
        $sender = $this->get("hexmedia.newsletter.sender");

        return new Response($sender->renderMail($sentTo), 200);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request, $id)
    {
        $mailRepo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Mail");
        $mail = $mailRepo->find($id);

        $person = new Person();
        $person->setName($request->get("to_name"));
        $person->setEmail($request->get("to"));

        $sentTo = new SentTo();
        $sentTo->setMail($mail);
        $sentTo->setPerson($person);

        $sender = $this->get("hexmedia.newsletter.sender");

        $sender->sendOne($sentTo, false);

        $this->get("hexmedia.templating.helper.mail_embed")->setEmbed(false);

        return new Response($sender->renderMail($sentTo), 200);
    }
}
