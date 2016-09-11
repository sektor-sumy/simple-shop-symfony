<?php
namespace AppBundle;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class AbstractCommand
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @return Registry
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->getContainer()->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->getContainer()->get('doctrine');
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
        return $this->getContainer()->get('logger');
    }
}
