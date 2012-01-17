<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base RPC Service class.
 *
 * @package    ItartRpcBundle
 * @subpackage Service
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
abstract class RpcService
{
    private $_container;

    /**
     * Construct.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    protected function get($id)
    {
        return $this->_container->get($id);
    }
}
