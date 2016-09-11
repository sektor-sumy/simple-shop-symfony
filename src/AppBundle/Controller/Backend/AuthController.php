<?php
namespace AppBundle\Controller\Backend;

use AppBundle\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/auth")
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="backend-user-login")
     * @Template()
     * @return array
     */
    public function loginAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('backend-dashboard');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ];
    }

    /**
     * @Route("/check", name="backend-user-login-check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="backend-user-logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
