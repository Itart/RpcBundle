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

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds tagged rpc.loader services to rpc.resolver service
 *
 * @package    ItartRpcBundle
 * @subpackage DependencyInjection
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class RpcResolverPass implements CompilerPassInterface
{
    /**
     * Add loader definition to resolver.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('rpc.resolver')) {
            return;
        }

        $definition = $container->getDefinition('rpc.resolver');

        foreach ($container->findTaggedServiceIds('rpc.loader') as $id => $attributes) {
            $definition->addMethodCall('addLoader', array(new Reference($id)));
        }
    }
}
