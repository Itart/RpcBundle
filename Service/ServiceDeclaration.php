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
 * RPC Service declaration.
 *
 * @package    ItartRpcBundle
 * @subpackage Service
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class ServiceDeclaration
{
    private $_class;

    /**
     * Return service class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }

    private $_service;

    /**
     * Return service id.
     *
     * @return string
     */
    public function getService()
    {
        return $this->_service;
    }

    private $_namespace;

    /**
     * Return service namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    private $_protocols = array();

    /**
     * Returns the flag service availability protocol.
     *
     * @param string $protocol RPC protocol name
     *
     * @return boolean
     */
    public function isProtocol($protocol)
    {
        if (!$this->_protocols) {
            return true;
        }

        return in_array($protocol, $this->_protocols);
    }

    /**
     * Constructor.
     *
     * @param array $options Service options
     *
     * @throws \LogicException
     */
    public function __construct(array $options)
    {
        $this->_service = isset($options['service']) ? $options['service'] : null;
        $this->_class = isset($options['class']) ? $options['class'] : null;

        if (!$this->_service && !$this->_class) {
            throw new \LogicException('In options must set "service" or "class" keys.');
        }

        $this->_namespace = isset($options['namespace']) ? $options['namespace'] : null;
        $this->_protocols = isset($options['protocols']) ? (array) $options['protocols'] : array();
    }
}
