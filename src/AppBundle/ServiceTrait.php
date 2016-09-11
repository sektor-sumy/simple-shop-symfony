<?php

namespace AppBundle;

use AppBundle\Service\UserService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\LoggingTranslator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @property ContainerInterface $container
 */
trait ServiceTrait
{
    /**
     * @param string $id
     * @return object
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @return Registry
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }
    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
    /**
     * @return \Symfony\Bridge\Monolog\Logger
     */
    protected function getLogger()
    {
        return $this->container->get('logger');
    }
    /**
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->container->get('mailer');
    }
    /**
     * @return LoggingTranslator
     */
    protected function getTranslator()
    {
        return $this->container->get('translator');
    }
    /**
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->container->get('validator');
    }
    /**
     * @return \Twig_Environment
     */
    protected function getTwig()
    {
        return $this->container->get('twig');
    }
    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->container->get('service.user');
    }

    /**
     * @param $query
     * @return array
     */
    protected function parseArticle($query)
    {
        $query = trim($query);
        $matches = [];
        preg_match('/^[^\d]+/', $query, $matches);
        $prefix = empty($matches[0]) ? null : strtoupper($matches[0]);
        $id = preg_replace('/[^\d]+/', '', $query);
        if (!strlen($id)) {
            $id = null;
        }

        return [$prefix, $id];
    }
}
