<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Itart\Bundle\RpcBundle\DependencyInjection\Compiler;

/**
 * An implementation of BundleInterface that adds a few conventions
 * for DependencyInjection extensions and Console commands.
 *
 * @package   ItartRpcBundle
 * @author    Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright 2012 Pavel Gopanenko
 *
 * @api
 */
class ItartRpcBundle extends Bundle
{
    /**
     * Builds the bundle.
     * Add Generator pass to compiler.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @see Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\RpcResolverPass());
        $container->addCompilerPass(new Compiler\RpcServerPass());
    }
}
