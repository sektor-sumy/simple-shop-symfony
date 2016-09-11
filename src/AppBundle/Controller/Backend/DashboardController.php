<?php

namespace AppBundle\Controller\Backend;

use AppBundle\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="backend-dashboard")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}
