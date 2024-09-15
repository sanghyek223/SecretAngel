<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\BoardCheck;
use App\Http\Middleware\XSSDefense;
use App\Http\Middleware\CryptoDecrypt;
use App\Http\Middleware\ViewShareMenu;

return Application::configure(basePath: dirname(__DIR__))

    // 라우팅 설정
    ->withRouting(
        using: function () {
            switch (checkUrl()) {
                case 'api':
                    // API 라우트 설정
                    Route::middleware('api')
                        ->prefix('api')
                        ->group(base_path('routes/api.php'));
                    break;

                case 'admin':
                    // Admin 라우트 설정
                    Route::middleware(['web', 'admin.check', 'cryptoDecrypt', 'XSS.defense', 'view.share.menu'])
                        ->prefix('admin')
                        ->group(base_path('routes/admin.php'));
                    break;

                default:
                    Route::middleware(['web', 'cryptoDecrypt', 'XSS.defense', 'view.share.menu'])
                        ->group(base_path('routes/web.php'));
                    break;
            }
        },
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up', // 헬스 체크 엔드포인트
    )

    // 미들웨어 설정
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.check' => AuthCheck::class,
            'admin.check' => AdminCheck::class,
            'board.check' => BoardCheck::class,
            'XSS.defense' => XSSDefense::class,
            'cryptoDecrypt' => CryptoDecrypt::class,
            'view.share.menu' => ViewShareMenu::class,
        ]);
    })

    // 예외 처리 설정
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, Request $request) {

            // 모델 조회 실패
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return notFoundRedirect();
            }

            // HTTP 예외 처리
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $statusCode = $e->getStatusCode();

                switch ($statusCode) {
                    case 404: // 404 Not Found
                        break;

                    case 419: // 419 CSRF Token Expired
                        return CSRFRedirect();

                    case 500: // 500 Internal Server Error
                        return serverRedirect();

                    case 543: // 커스텀 에러 리다이렉트
                        return handleCustomRedirect();

                    default: // 기타 HTTP 에러 처리
                        return errorRedirect('replace', "{$statusCode} ERROR", getDefaultUrl());
                }
            }

            // 기타 예외 처리
            if (!isDev()) {
                return serverRedirect(); // 개발자가 아닌 경우 메인 페이지로 이동
            }
        });

    })

    // 생성
    ->create();
