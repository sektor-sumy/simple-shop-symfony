<?php

namespace AppBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\AbstractController;

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="frontend-homepage")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}
