<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged rpc.server server to rpc.server.builder service
 *
 * @package    ItartRpcBundle
 * @subpackage DependencyInjection
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class RpcServerPass implements CompilerPassInterface
{
    /**
     * Add protocol server to server builder.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('rpc.server.builder')) {
            return;
        }

        $definition = $container->getDefinition('rpc.server.builder');

        foreach ($container->findTaggedServiceIds('rpc.server') as $id => $attributes) {
            if (!isset($attributes[0]['protocol'])) {
                throw new \LogicException('RPC Server definition mus contains tag "protocol".');
            }
            $definition->addMethodCall('registeredServer', array($attributes[0]['protocol'], $id));
        }
    }
}
