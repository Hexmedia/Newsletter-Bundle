<?php

namespace Hexmedia\NewsletterBundle\Controller;

use Hexmedia\AdministratorBundle\Controller\CrudController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hexmedia\NewsletterBundle\Form\Type\Person\UploadForm;
use Hexmedia\NewsletterBundle\Repository\Doctrine\PersonRepository;
use Hexmedia\NewsletterBundle\Entity\Person;
use Hexmedia\NewsletterBundle\Form\Type\Person\AddType;
use Hexmedia\NewsletterBundle\Form\Type\Person\EditType;
use Symfony\Component\HttpFoundation\Request;

class AdminPersonController extends Controller
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
        return "HexMediaNewsletterPerson";
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
        return "HexmediaNewsletterBundle:AdminPerson";
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
     * @return Person
     */
    protected function getNewEntity()
    {
        return new Person();
    }

    /**
     * @return PersonRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('HexmediaNewsletterBundle:Person');
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

    /**
     * @param int $page
     * @return array
     *
     * @Rest\View(template="HexmediaNewsletterBundle:AdminPerson:list.html.twig")
     */
    public function listAction($page = 1) {
        return parent::listAction($page);
    }

    /**
     * @Rest\View(template="HexmediaNewsletterBundle:AdminPerson:import.html.twig")
     */
    public function importAction(Request $request)
    {
        $form = $this->createForm(new UploadForm());

        if ($form instanceof \Symfony\Component\Form\Form) ;

        if ($request->getMethod() == "POST") {
            $entityManager = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository("HexmediaNewsletterBundle:Person");

            $form->handleRequest($request);
            if ($form->isValid()) {
                foreach ($form->getData("files") as $file) {
                    $content = file_get_contents($file->getPathname());

                    $lines = explode("\n", $content);

                    foreach ($lines as $line) {
                        $exp = explode(";", $line);
                        $email = $exp[1];
                        $name = $exp[0];

                        $person = $repo->findOneByEmail($email);

                        if (!($person instanceof Person)) {
                            $person = new Person();

                            $person->setName($name);
                            $person->setEmail($email);

                            $entityManager->persist($person);
                        }
                    }

                    $entityManager->flush();
                }

                $this->get('session')->getFlashBag()->add('notice', 'Newsletter users has been imported.');

                return $this->redirect($this->get("router")->generate("HexMediaNewsletterPerson"));
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Can\'t upload file.');
            }
        }

        return [
            'form' => $form
        ];

    }
}
