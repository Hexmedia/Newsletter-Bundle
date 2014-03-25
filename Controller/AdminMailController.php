<?php

namespace Hexmedia\NewsletterBundle\Controller;

use CinemaForum\CatalogBundle\Entity\Sponsor;
use Hexmedia\AdministratorBundle\Controller\CrudController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hexmedia\NewsletterBundle\Entity\Person;
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
                    $message = $this->getMessage($mail, $person);

                    $failures = [];
                    $this->get('mailer')->send($message, $failures);

                    $person->addMail($mail);
                    $entityManager->persist($person);
                }
            }

            $mail->setSent(new \DateTime());

            $entityManager->persist($mail);
            $entityManager->flush();
        }

        return $this->redirect($this->get("router")->generate("HexMediaNewsletterMail"));
    }

    /**
     * @param Mail $mail
     * @param Person $person
     * @return \Swift_Message
     */
    private function getMessage(Mail $mail, Person $person)
    {
        $helper = $this->get("hexmedia.templating.helper.mail_embed");

        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getTitle())
            ->setFrom($this->container->getParameter("newsletter_from"), $this->container->getParameter("newsletter_formname"))
            ->setTo($person->getEmail(), $person->getName())
            ->setCharset("UTF-8")
            ->setContentType("text/html");

        $helper->setEmbed(true);
        $helper->setMessage($message);

        $message
            ->setBody($this->renderMessage($mail, $person, $message), 'text/html');

        $helper->setEmbed(false);
        $helper->setMessage(null);

        return $message;
    }

    private function renderMessage(Mail $mail, Person $person, \Swift_Message $message = null)
    {
        $html = $this->renderView(
            $this->container->getParameter("newsletter_template"),
            [
                'content' => $mail->getContent(),
                'title' => $mail->getTitle(),
                'name' => $person->getName(),
                'email' => $person->getEmail(),
                'message' => $message,
            ]
        );

        $css = $this->renderView(
            $this->container->getParameter("newsletter_css"),
            []
        );

        $converter = new CssToInlineStyles($html, $css);
        $view = $converter->convert();

        return $view;
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

        $this->get("hexmedia.templating.helper.mail_embed")->setEmbed(false);

        return new Response($this->renderMessage($mail, $person), 200);
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

        $message = $this->getMessage($mail, $person);

        $failures = [];

        $this->get("mailer")->send($message, $failures);

        return new Response($this->renderMessage($mail, $person), 200);
    }
}
