<?php
namespace AppBundle;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Service
 */
abstract class AbstractService
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage, Registry $doctrine)
    {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
    }

    /**
     * @return Registry
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {

        return $this->doctrine;
    }
    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        $user = null;
        $token = $this->tokenStorage->getToken();
        if (null !== $token && $token->getUser() instanceof User) {
            $user = $token->getUser();
        }

        return $user;
    }
}
