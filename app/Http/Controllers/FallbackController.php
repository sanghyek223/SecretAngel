<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FallbackController extends Controller
{
    public function handle(Request $request)
    {
        // 요청된 URI에서 www 제거
        $uri = str_replace('://www.', '://', $request->getUri());

        // favicon.ico에 대한 요청일 경우 404 반환
        if (strpos($uri, 'favicon.ico') !== false) {
            return abort(404);
        }

        // public 폴더 내 파일 및 폴더 목록 가져오기, 숨김 파일 및 특정 파일 제외
        $exclude = ['robots.txt', 'index.php'];
        $filesAndFolders = array_filter(scandir(public_path()), function ($item) use ($exclude) {
            return $item[0] !== '.' && !in_array($item, $exclude);
        });

        // 요청된 URI가 public 폴더 내 파일에 해당하는지 검사
        $baseUrl = rtrim(env('APP_URL'), '/');
        foreach ($filesAndFolders as $value) {
            $customUrl = $baseUrl . '/' . ltrim($value, '/'); // URL 조합 시 중복 슬래시 제거

            if (strpos($uri, $customUrl) !== false) {
                return abort(404); // 퍼블릭 경로에 해당하는 경우 404 반환
            }
        }

        // 리다이렉션 처리 함수 호출
        return notFoundRedirect();
    }
}
