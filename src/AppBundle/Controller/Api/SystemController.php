<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/system")
 */
class SystemController extends AbstractController
{
    /**
     * @Route("/v1", name="api-system-v1")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function v1Action(Request $request)
    {
        return [];
    }
}
