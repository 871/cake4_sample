<?php

namespace App\Middleware\Auth;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\RedirectResponse;


class AdminAuthMiddleware implements MiddlewareInterface
{
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authSessionKey = ADMIN_AUTH_KEY  . '.' . (string) $request->getParam('admin_account_id');
        
        $url = '/ad/login?redirect=' . urlencode($request->getUri()->getPath() . '?' . $request->getUri()->getQuery());
        
        return $request->getSession()->check($authSessionKey)
                ? $handler->handle($request)
                : new RedirectResponse($url);
    }
}
