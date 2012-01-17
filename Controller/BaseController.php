<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Abstract RPC controller.
 *
 * @package    ItartRpcBundle
 * @subpackage Controller
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
abstract class BaseController extends Controller
{
    /**
     * Create HTTP response instance.
     *
     * @param string $content     Content value
     * @param string $contentType Content-Type header value
     *
     * @return Response
     */
    protected function createResponse($content, $contentType = null)
    {
        $response = new Response();

        if ($contentType) {
            $response->headers->set('Content-Type', $contentType);
        }

        $response->setContent((string) $content);
        return $response;
    }
}
