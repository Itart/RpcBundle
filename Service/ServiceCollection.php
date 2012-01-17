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

/**
 * RPC Service collection.
 *
 * @package    ItartRpcBundle
 * @subpackage Service
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class ServiceCollection implements \IteratorAggregate
{
    private $_services;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_services = array();
    }

    /**
     * Add service declaration to collection.
     *
     * @param string             $name    Declaration name
     * @param ServiceDeclaration $service A ServiceDeclaration interface
     *
     * @throws \InvalidArgumentException
     */
    public function add($name, ServiceDeclaration $service)
    {
        if (isset($this->_services[$name])) {
            throw new \InvalidArgumentException(sprintf('Web service by name "%s" alredy exists.', $name));
        }

        $this->_services[$name] = $service;
    }

    /**
     * Return service declaration iterator.
     *
     * @return ArrayIterator
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_services);
    }

    /**
     * Add another service collection to current collection.
     *
     * @param ServiceCollection $collection A ServiceCollection instance
     */
    public function addCollection(ServiceCollection $collection)
    {
        foreach ($collection->getIterator() as $name => $service) {
            $this->add($name, $service);
        }
    }
}
