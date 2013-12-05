<?php

namespace Hexmedia\NewsletterBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class AdminDashboardController extends Controller
{
    /**
     * @return array
     *
     * @Rest\View
     */
    public function dashboardAction()
    {
        return [];
    }

}
