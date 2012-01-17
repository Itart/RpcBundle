<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Server;

/**
 * RPC Server interface.
 *
 * @package    ItartRpcBundle
 * @subpackage Server
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
interface ServerInterface
{
    /**
     * Add service methods to server.
     *
     * @param string $id        Service id in service container
     * @param string $namespace Method namespace
     */
    function addService($id, $namespace = null);

    /**
     * Add class methods to server.
     *
     * @param string $class     Class name
     * @param string $namespace Method namespace
     */
    function addClass($class, $namespace = null);
}
