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

class AdminSentToController extends Controller
{
    /**
     * @return AddType
     */
    protected function getAddFormType()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return "HexMediaNewsletterSentTo";
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
        return "HexmediaNewsletterBundle:AdminSentTo";
    }

    /**
     * @param int $page
     * @param string $sort
     * @param string $direction
     * @return array
     *
     * @Rest\View
     */
    public function indexAction($page = 1, $sort = "obj.id", $direction = "desc")
    {
        $ret = array_merge(parent::indexAction($page, $sort, $direction), ['id' => $this->getRequest()->get("id")]);

        return $ret;
    }

    /**
     * @param int $page
     * @return array|void
     *
     * @Rest\View(template="HexmediaNewsletterBundle:AdminSentTo:list.html.twig")
     */
    public function listAction($page = 1)
    {
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

        $this->breadcrumbs->addItem(
            $this->get('translator')->trans("Sent to")
        );

        return $this->breadcrumbs;
    }

    /**
     * @return Mail
     */
    protected function getNewEntity()
    {
        return new SentTo();
    }

    /**
     * @return MailRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('HexmediaNewsletterBundle:SentTo');
    }

    /**
     * @return EditType
     */
    protected function getEditFormType()
    {
        return null;
    }
}
