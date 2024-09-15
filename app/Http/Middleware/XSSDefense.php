<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class XSSDefense
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
        // 사용자 입력 필터링
        $input = $request->all();
        $this->sanitizeInput($input);
        $request->replace($input);

        return $next($request);
    }

    private function sanitizeInput(array &$input)
    {
        foreach ($input as $key => &$value) {
            switch (gettype($value)) {
                case 'array':
                    // 배열인 경우 재귀적으로 호출
                    $this->sanitizeInput($value);
                    break;

                case 'string':
                    // 문자열인 경우에만 HTMLPurifier를 사용하여 안전한 HTML로 변환
                    $value = Purifier::clean($value);
                    $value = htmlspecialchars_decode($value);
                    break;

                default:
                    break;
            }
        }
    }
}
