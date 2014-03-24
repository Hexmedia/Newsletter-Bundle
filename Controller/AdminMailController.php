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

        if ($mail instanceof Mail) ;

        $persons = $personRepo->findAll();

        $mailContent = $mail->getContent();

        $asset = $this->get('templating.helper.assets');

        foreach ($persons as $person) {
            if ($person instanceof Person) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($mail->getTitle())
                    ->setFrom($this->container->getParameter("newsletter_from"), $this->container->getParameter("newsletter_formname"))
                    ->setTo($person->getEmail())
                    ->setCharset("UTF-8")
                    ->setContentType("text/html");
                $message
                    ->setBody(
                        $this->renderView(
                            $this->container->getParameter("newsletter_template"),
                            [
                                'content' => $mail->getContent(),
                                'title' => $mail->getTitle(),
                                'header1' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header1.jpg"))),
                                'header2' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header2.jpg"))),
                                'header3' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header3.jpg"))),
                                'sponsors' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/sponsors.gif"))),
                                'spacer1' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/spacer1.jpg"))),
                                'spacer2' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/spacer2.gif"))),
                                'place' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/place.jpg"))),
                                'mail' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/mail.jpg"))),
                                'phone' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/phone.jpg"))),
                                'apollo' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/apollo.jpg")))
                            ]
                        ), 'text/html'
                    );

                $mail->addPerson($person);


                $this->get('mailer')->send($message);
            }
        }

        $mail->setSent(new \DateTime());

        $entityManager->persist($mail);
        $entityManager->flush();

        return $this->redirect($this->get("router")->generate("HexMediaNewsletterMail"));
    }

    private function getSwiftImage($path)
    {
        return \Swift_Image::fromPath($this->getRequest()->getSchemeAndHttpHost() . $path);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction(Request $request)
    {
        $asset = $this->get('templating.helper.assets');
        $liip = $this->get('liip_imagine.cache.manager');

        if ($liip instanceof \Liip\ImagineBundle\Imagine\Cache\CacheManager) ;

        return $this->render(
            $this->container->getParameter("newsletter_template"),
            [
                'content' => $request->get("content"),
                'title' => $request->get("title"),
                'header1' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header1.jpg"),
                'header2' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header2.jpg"),
                'header3' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header3.jpg"),
                'sponsors' => $asset->getUrl("bundles/cinemaforumnewsletter/img/sponsors.gif"),
                'spacer1' => $asset->getUrl("bundles/cinemaforumnewsletter/img/spacer1.jpg"),
                'spacer2' => $asset->getUrl("bundles/cinemaforumnewsletter/img/spacer2.gif"),
                'place' => $asset->getUrl("bundles/cinemaforumnewsletter/img/place.jpg"),
                'mail' => $asset->getUrl("bundles/cinemaforumnewsletter/img/mail.jpg"),
                'phone' => $asset->getUrl("bundles/cinemaforumnewsletter/img/phone.jpg"),
                'apollo' => $asset->getUrl("bundles/cinemaforumnewsletter/img/apollo.jpg")
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request)
    {

        $asset = $this->get('templating.helper.assets');
        $message = \Swift_Message::newInstance()
            ->setSubject($request->get("title"))
            ->setFrom($this->container->getParameter("newsletter_from"), $this->container->getParameter("newsletter_formname"))
            ->setTo($request->get("to"), "testowy email")
            ->setCharset("UTF-8")
            ->setContentType("text/html");
        $message
            ->setBody(
                $this->renderView(
                    $this->container->getParameter("newsletter_template"),
                    [
                        'content' => $request->get("content"),
                        'title' => $request->get("title"),
                        'header1' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header1.jpg"))),
                        'header2' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header2.jpg"))),
                        'header3' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/header3.jpg"))),
                        'spacer1' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/spacer1.jpg"))),
                        'spacer2' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/spacer2.gif"))),
                        'place' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/place.jpg"))),
                        'mail' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/mail.jpg"))),
                        'phone' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/phone.jpg"))),
                        'apollo' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/apollo.jpg"))),
                        'sponsors' => $message->embed($this->getSwiftImage($asset->getUrl("bundles/cinemaforumnewsletter/img/sponsors.gif"))),
                    ]
                ), 'text/html'
            );


        $failures = [];

        $this->get('mailer')->send($message, $failures);

        var_dump($failures);

        return $this->render(
            $this->container->getParameter("newsletter_template"),
            [
                'content' => $request->get("content"),
                'title' => $request->get("title"),
                'header1' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header1.jpg"),
                'header2' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header2.jpg"),
                'header3' => $asset->getUrl("bundles/cinemaforumnewsletter/img/header3.jpg"),
                'sponsors' => $asset->getUrl("bundles/cinemaforumnewsletter/img/sponsors.gif"),
                'spacer1' => $asset->getUrl("bundles/cinemaforumnewsletter/img/spacer1.jpg"),
                'spacer2' => $asset->getUrl("bundles/cinemaforumnewsletter/img/spacer2.gif"),
                'place' => $asset->getUrl("bundles/cinemaforumnewsletter/img/place.jpg"),
                'mail' => $asset->getUrl("bundles/cinemaforumnewsletter/img/mail.jpg"),
                'phone' => $asset->getUrl("bundles/cinemaforumnewsletter/img/phone.jpg"),
                'apollo' => $asset->getUrl("bundles/cinemaforumnewsletter/img/apollo.jpg")
            ]
        );
    }
}
