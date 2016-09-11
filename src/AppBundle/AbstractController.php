<?php

namespace AppBundle;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Controller
 *
 * show off @method
 *
 * @method void onKernelController(FilterControllerEvent $event)
 * @method void onKernelResponse(FilterResponseEvent $event)
 * @method void onKernelException(GetResponseForExceptionEvent $event)
 * @method void onKernelFinishRequest(FinishRequestEvent $event)
 * @method void onKernelView(GetResponseForControllerResultEvent $event)
 * @method void onKernelTerminate(PostResponseEvent $event)
 * @property ContainerInterface $container
 */
abstract class AbstractController extends BaseController
{
    use ServiceTrait;

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     *
     * @return string
     */
    public function path($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->generateUrl($route, $parameters, $referenceType);
    }

    /**
     * @return object|\Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return parent::getUser();
    }

    /**
     * @param string     $value
     * @param int        $length
     * @param bool|false $preserve
     * @param string     $separator
     * @return string
     */
    public function truncate($value, $length = 255, $preserve = false, $separator = '')
    {
        $filters = $this->get('twig.extension.text')->getFilters();
        $callable = $filters[0]->getCallable();

        return (string) $callable($this->get('twig'), $value, $length, $preserve, $separator);
    }

    /**
     * Filter page meta description
     *
     * @param string $value
     * @return string
     */
    public function filterMetaDescription($value)
    {
        $value = strip_tags($value);
        $value = preg_replace('#[\s\r\n]+#', ' ', $value);

        return $this->truncate($value, 250, false, '');
    }


    /**
     * @param Request $request
     * @param string  $defaultRoute
     * @param array   $defaultParams
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToLastRoute($request, $defaultRoute, $defaultParams = [])
    {
        $referer  = $request->headers->get('referer');
        $baseUrl  = $request->getSchemeAndHttpHost();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        try {
            $params = $this->get('router')->getMatcher()->match($lastPath);
            $route  = $params['_route'];

            unset($params['_route'], $params['_controller'], $params['domain']);

            return $this->redirectToRoute($route, $params);
        } catch (ResourceNotFoundException $e) {
            return $this->redirectToRoute($defaultRoute, $defaultParams);
        }
    }
}
