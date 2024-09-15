<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BoardCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $code = $request->code; // 게시판 code
        $config = getConfig('board')[$code] ?? null; // 게시판 config

        if (is_null($config)) {
            return notFoundRedirect();
        }

        // 로그인 사용시 로그인 안되어 있다면
        if($config['use']['login'] && !thisAuth()->check()) {
            return authRedirect();
        }

        // 관리자 계정이 아닐경우 권한체크
        if (!isAdmin()) {
            $userLevel = thisLevel(); // 회원 권한

            // 접근 권한이 설정되어있을 경우 해당 level 체크
            switch ($request->route()->getName()) {
                case 'board': // 리스트 권한
                    $listPermission = $config['permission']['list'];

                    if (!empty($listPermission) && !in_array($userLevel, $listPermission)) {
                        return denyRedirect();
                    }
                    break;

                case 'board.upsert': // 글쓰기 or 수정 권한
                    $writePermission = $config['permission']['write'];

                    if (!empty($writePermission) && !in_array($userLevel, $writePermission)) {
                        return denyRedirect();
                    }
                    break;

                case 'board.view': // 상세페이지 권한
                    $viewPermission = $config['permission']['view'];

                    if (!empty($viewPermission) && !in_array($userLevel, $viewPermission)) {
                        return denyRedirect();
                    }
                    break;

                default:
                    break;
            }
        }

        return $next($request);
    }
}
