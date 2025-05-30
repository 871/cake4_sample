<?php
declare(strict_types=1);

namespace App\Middleware\AuthorizationServiceProvider;


class AdminAuthProvider implements \Authorization\AuthorizationServiceProviderInterface
{
    public function getAuthorizationService(\Cake\Http\ServerRequest $request): \Authorization\AuthorizationServiceInterface
    {
        $authSessionKey = ADMIN_AUTH_KEY  . '.' . (string) $request->getParam('admin_account_id');
        
        $request->getSession()->check($authSessionKey);
        

        return $service;
    }
}