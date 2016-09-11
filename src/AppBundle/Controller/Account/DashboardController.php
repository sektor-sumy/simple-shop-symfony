<?php

namespace AppBundle\Controller\Account;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\AbstractController;

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="account-dashboard")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}
