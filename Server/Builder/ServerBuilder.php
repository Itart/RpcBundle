<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Server\Builder;

use Itart\Bundle\RpcBundle\Service\ServiceCollection;
use Itart\Bundle\RpcBundle\Server\ServerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Load RPC extension features.
 *
 * @package    ItartRpcBundle
 * @subpackage Server
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class ServerBuilder extends ContainerAware
{
    private $_services = array();

    private $_collection;

    /**
     * Contructor.
     *
     * @param string $resource Configuration declaration resource
     */
    public function __construct($resource)
    {
        $this->_resource = $resource;
    }

    /**
     * Registered service id for protocol.
     *
     * @param string $protocol Protocol name
     * @param string $service  Service id in service container
     */
    public function registeredServer($protocol, $service)
    {
        $this->_services[$protocol] = $service;
    }

    /**
     * Returns the protocol for the services, if not specified, all services.
     *
     * @param string $protocol Protocol name
     *
     * @return array Services array
     */
    public function getServiceCollection($protocol = null)
    {
        if (null === $this->_collection) {
            $this->_collection = $this->container->get('rpc.loader')->load($this->_resource, null);
        }

        $result = array();

        foreach ($this->_collection->getIterator() as $service) {
            if ($service->isProtocol($protocol)) {
                $result[] = $service;
            }
        }

        return $result;
    }

    /**
     * Build service instance for protocol.
     *
     * @param string $protocol Protocol name
     *
     * @return ServerInterface Server interface
     *
     * @throws InvalidArgumentException Throws if protocol not supported
     * @throws LogicException           Throws if server instance not implementing ServerInterface
     */
    public function buildServer($protocol)
    {
        if (!isset($this->_services[$protocol])) {
            throw new InvalidArgumentException(sprintf('RPC protocol "%s" not supported.', $protocol));
        }

        $server = $this->container->get($this->_services[$protocol]);
        if (!$server instanceof ServerInterface) {
            throw new LogicException('Rpc server must implementatig "ServerInterface" interface.');
        }

        foreach ($this->getServiceCollection($protocol) as $service) {

            if (!$service->isProtocol($protocol)) {
                continue;
            }

            $ns = $service->getNamespace();
            if ($service->getService()) {
                $server->addService($service->getService(), $ns);
            } else {
                $server->addClass($service->getClass(), $ns);
            }
        }

        return $server;
    }
}
