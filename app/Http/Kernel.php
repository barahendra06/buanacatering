<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // 'sync.token' => \App\Http\Middleware\SyncTokenMiddleware::class,
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'news' => \App\Http\Middleware\NewsMiddleware::class,
        'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
        'poll' => \App\Http\Middleware\PollMiddleware::class,
        'content' => \App\Http\Middleware\ContentMiddleware::class,
        'member' => \App\Http\Middleware\MemberMiddleware::class,
        'photo' => \App\Http\Middleware\PhotoMiddleware::class,
        'post' => \App\Http\Middleware\PostMiddleware::class,
        'comment' => \App\Http\Middleware\CommentMiddleware::class,
        'infographic' => \App\Http\Middleware\InfographicMiddleware::class,
        'tag' => \App\Http\Middleware\TagMiddleware::class,
        'challenge' => \App\Http\Middleware\ChallengeMiddleware::class,
        'api.whitelist' => \App\Http\Middleware\ApiAccess::class,
        'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
        'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
        'jwt.admin' => \App\Http\Middleware\AdminApiMiddleware::class,
    	'event' => \App\Http\Middleware\EventMiddleware::class,
        'conversation' => \App\Http\Middleware\ConversationMiddleware::class,
        'notification' => \App\Http\Middleware\NotificationMiddleware::class,
        'check.profile' => \App\Http\Middleware\CheckProfileMiddleware::class,
        'newsletter' => \App\Http\Middleware\NewsletterMiddleware::class,
        'league' => \App\Http\Middleware\LeagueMiddleware::class,
        'check.coach' => \App\Http\Middleware\CheckAuthorizeCoachInClass::class,
        'sync.token' => \App\Http\Middleware\SyncTokenMiddleware::class,
    ];
}
