<?php

namespace AppBundle\Controller\Frontend;

use AppBundle\Form\Frontend\UserPasswordRecoveryForm;
use AppBundle\Form\Frontend\UserRegisterForm;
use AppBundle\Form\Frontend\UserRestoreForm;
use AppBundle\Exception\BasicException;
use AppBundle\Exception\UserAlreadyRegisteredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception as SecurityException;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="frontend-user-register")
     * @Template
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('account-dashboard');
        }
        $form = $this->createForm(UserRegisterForm::class);
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->getEm()->beginTransaction();
                try {
                    $this->getUserService()->register($form->getData());
                    $this->getEm()->commit();

                    return $this->redirectToRoute('frontend-user-register-success');
                } catch (UserAlreadyRegisteredException $e) {
                    $this->getEm()->rollback();
                    $form->get('email')->addError(new FormError('User already register!'));
                } catch (BasicException $e) {
                    $this->getEm()->rollback();
                    $this->addFlash('error', $e->getMessage());
                    $this->getLogger()->critical($e->getMessage(), ['exception' => $e]);
                } catch (\Exception $e) {
                    $this->getEm()->rollback();
                    $message = $this->getTranslator()->trans('Unfortunately registration failed. We have already received an issue notification and will try to fix it as soon as possible.');
                    $this->addFlash('error', $message);
                    $this->getLogger()->critical($e->getMessage(), ['exception' => $e]);
                }
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }
    /**
     * @Route("/login", name="frontend-user-login")
     * @Template
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('account-dashboard');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        $err = $authenticationUtils->getLastAuthenticationError();

        return [
            'error' => $err,
            'last_username' => $authenticationUtils->getLastUsername(),
        ];
    }

    /**
     * @Route("/register/success", name="frontend-user-register-success")
     * @Template()
     * @return array
     */
    public function registerSuccessAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('account-dashboard');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        $err = $authenticationUtils->getLastAuthenticationError();

        return [
            'error' => $err,
            'last_username' => $authenticationUtils->getLastUsername(),
        ];
    }

    /**
     * @Route("/login-check", name="frontend-user-login-check")
     * @return RedirectResponse
     */
    public function loginCheckAction()
    {
        return $this->redirectToRoute('frontend-user-login');
    }

    /**
     * @Route("/logout", name="frontend-user-logout")
     * @return RedirectResponse
     */
    public function logoutAction()
    {
        return $this->redirectToRoute('frontend-user-login');
    }
}
