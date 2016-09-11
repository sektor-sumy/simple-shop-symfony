<?php

namespace AppBundle\Doctrine\ORM\Hydration;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

/**
 * Class PairsHydrator
 */
class PairsHydrator extends AbstractHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function hydrateAllData()
    {
        return $this->_stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
