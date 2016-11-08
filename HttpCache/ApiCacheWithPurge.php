<?php
/*
 * This file is based on HttpCache which is part of the Silex framework and 
 * https://symfony.com/doc/current/http_cache/cache_invalidation.html.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Silex\Provider\HttpCache;

use Symfony\Component\HttpKernel\HttpCache\HttpCache as BaseHttpCache;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\HttpCach\HttpCache;

class ApiCacheWithPurge extends HttpCache
{
    protected function invalidate(Request $request, $catch = false)
    {
        if ('PURGE' !== $request->getMethod()) {
            return parent::invalidate($request, $catch);
        }

        if ('127.0.0.1' !== $request->getClientIp()) {
            return new Response(
                'Invalid HTTP method',
                Response::HTTP_BAD_REQUEST
            );
        }

        $response = new Response();
        if ($this->getStore()->purge($request->getUri())) {
            $response->setStatusCode(200, 'Purged');
        } else {
            $response->setStatusCode(404, 'Not found');
        }

        return $response;
    }
}
