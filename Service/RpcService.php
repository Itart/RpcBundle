<?php

namespace Itart\Bundle\RpcBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class RpcService
{
    private $_container;

    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    protected function get($id)
    {
        return $this->_container->get($id);
    }
}
