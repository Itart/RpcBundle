<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Config\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Description of the annotation service.
 *
 * @package    ItartRpcBundle
 * @subpackage Config
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 *
 * @Annotation
 */
final class Service extends Annotation
{
    /**
     * Methods namespace.
     *
     * @var string
     */
    public $namespace = null;

    /**
     * The service name that will be asked for a copy of the container instead of creating a new.
     *
     * @var string
     */
    public $service = null;

    /**
     * An array of protocols that provide service.
     *
     * @var string
     */
    public $protocols = null;
}
