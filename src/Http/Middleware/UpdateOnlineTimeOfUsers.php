<?php

namespace dnj\AAA\Http\Middleware;

use dnj\AAA\Contracts\IUserManager;
use Illuminate\Http\Request;

class UpdateOnlineTimeOfUsers
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $user = $request->user();
        if ($user) {
            /**
             * @var IUserManager
             */
            $userManager = app(IUserManager::class);
            $userManager->ping($user);
        }

        return $next($request);
    }
}
