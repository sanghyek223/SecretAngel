<?php

require_once('SiteHelper.php');

// global user
if (!function_exists('thisUser')) {
    function thisUser()
    {
        return thisAuth()->user();
    }
}

// get user pk
if (!function_exists('thisPk')) {
    function thisPK(): int
    {
        return thisAuth()->id() ?? 0;
    }
}

// set Flash session
if (!function_exists('setFlashData')) {
    function setFlashData(array $data): void
    {
        foreach ($data as $key => $val) {
            session()->flash($key, $val);
        }
    }
}

// get config
if (!function_exists('getConfig')) {
    function getConfig(string $code)
    {
        return config('site')[$code];
    }
}

// error msg
if (!function_exists('errorMsg')) {
    function errorMsg(string $code): string
    {
        return getConfig('error')[$code] ?? $code;
    }
}

// DB ERROR
if (!function_exists('dbRedirect')) {
    function dbRedirect()
    {
        return errorRedirect('reload', 'db');
    }
}

// 접근권한 없음
if (!function_exists('denyRedirect')) {
    function denyRedirect()
    {
        return errorRedirect('back', 'deny');
    }
}

// 로그인 필요
if (!function_exists('authRedirect')) {
    function authRedirect()
    {
        return errorRedirect('replace', 'auth');
    }
}

// SERVER ERROR
if (!function_exists('serverRedirect')) {
    function serverRedirect()
    {
        return errorRedirect('replace', '500');
    }
}

// 404 not found
if (!function_exists('notFoundRedirect')) {
    function notFoundRedirect()
    {
        return errorRedirect('replace', '404');
    }
}

// 419 CSRF Token Expires
if (!function_exists('CSRFRedirect')) {
    function CSRFRedirect()
    {
        return errorRedirect('back', '419');
    }
}

// custom error redirect
if (!function_exists('errorRedirect')) {
    function errorRedirect(string $redirect, string $code, $url = null)
    {
        /*
         * $redirect 종류
         * back, reload, replace
         * back => 뒤로
         * reload => 새로고침
         * replace => 페이지 이동, (url 변수 필요 없을경우 메인페이지)
         *
         * $code
         * config 에 설정된 site.error 키값이 없을경우 변수값으로 에러메세지 출력
         */

        $referer = request()->headers->get('referer');

        $json = [
            'msg' => errorMsg($code),
            'url' => $url ?? getDefaultUrl($code == 'auth'),
            'redirect' => $redirect,
        ];

        if (request()->ajax()) {
            unset($json['url']);
            unset($json['redirect']);

            $json['case'] = true;
            $json['location'] = ['case' => 'reload'];
            return response()->json(['alert' => $json]);
        } else {
            // 뒤로가인데 뒤로갈 페이지 없을경우
            if ($redirect === 'back' && empty($referer)) {
                $json['redirect'] = 'replace';
                $json['url'] = $url ?? getDefaultUrl($code == 'auth');
            }

            setFlashData($json);
            abort(543); // 커스텀 에러 발생 시키기;
        }
    }
}

// custom error redirect
if (!function_exists('errorRedirect')) {
    function errorRedirect(string $redirect, string $code, $url = null)
    {
        /*
         * $redirect 종류
         * back, reload, replace
         * back => 뒤로
         * reload => 새로고침
         * replace => 페이지 이동, (url 변수 필요 없을경우 메인페이지)
         *
         * $code
         * config 에 설정된 site.error 키값이 없을경우 변수값으로 에러메세지 출력
         */

        $referer = request()->headers->get('referer');

        $json = [
            'msg' => errorMsg($code),
            'url' => $url ?? getDefaultUrl($code == 'auth'),
            'redirect' => $redirect,
        ];

        if (request()->ajax()) {
            unset($json['url']);
            unset($json['redirect']);

            $json['ajax'] = true;
        } else {
            // 뒤로가인데 뒤로갈 페이지 없을경우
            if ($redirect === 'back' && empty($referer)) {
                $json['redirect'] = 'replace';
                $json['url'] = $url ?? getDefaultUrl($code == 'auth');
            }
        }

        setFlashData($json);
        abort(543); // 커스텀 에러 발생 시키기;
    }
}

// 커스텀 리다이렉트 처리 함수
if (!function_exists('handleCustomRedirect')) {
    function handleCustomRedirect()
    {
        $redirectType = session()->pull('redirect');
        $message = session()->pull('msg');
        $url = session()->pull('url');

        if (session()->pull('ajax')) {
            return response()->json(['alert' => [
                'case' => true,
                'msg' => $message,
                'location' => ['case' => 'reload'],
            ]]);
        }

        switch ($redirectType) {
            case 'back':
                return redirect()->back()->with(['msg' => $message]);

            case 'reload':
                return redirect()->refresh()->with(['msg' => $message]);

            case 'replace':
                return redirect($url)->with(['msg' => $message]);

            default:
                return redirect(getDefaultUrl());
        }
    }
}

// set list seq (paging 있을때)
if (!function_exists('setListSeq')) {
    function setListSeq(object $data)
    {
        $count = 0;
        $total = $data->total();
        $perPage = $data->perPage();
        $currentPage = $data->currentPage();

        // seq 라는 순번 필드를 추가
        $data->getCollection()->transform(function ($data) use ($total, $perPage, $currentPage, &$count) {
            $data->seq = ($total - ($perPage * ($currentPage - 1))) - $count;
            $count++;
            return $data;
        });

        return $data;
    }
}

// set array seq (array 형태일때)
if (!function_exists('setArraySeq')) {
    function setArraySeq(array $data)
    {
        $count = 0;
        $total = count($data);

        // seq 라는 순번 필드를 추가
        foreach ($data as $key => $row) {
            $data[$key]['seq'] = $total - $count;
            $count++;
        }

        return $data;
    }
}

// Crypt::encryptString 적용
if (!function_exists('enCryptString')) {
    function enCryptString($string): string
    {
        return \Illuminate\Support\Facades\Crypt::encryptString($string);
    }
}

// Crypt::decryptString 적용
if (!function_exists('deCryptString')) {
    function deCryptString($string): string
    {
        return \Illuminate\Support\Facades\Crypt::decryptString($string);
    }
}

// jsonUnicode 적용
if (!function_exists('jsonUnicode')) {
    function jsonUnicode($aray = [])
    {
        return json_encode($aray, JSON_UNESCAPED_UNICODE);
    }
}

// 숫자 앞에 0 붙이기
if (!function_exists('addZero')) {
    function addZero(int $num, int $len)
    {
        return sprintf("%0{$len}d", $num);
    }
}

// 숫자 콤마 제거
if (!function_exists('unComma')) {
    function unComma(string $num)
    {
        return (int)str_replace(',', '', $num);
    }
}

// 날짜별 요일 YYYY-MM-DD
if (!function_exists('getYoil')) {
    function getYoil(string $date)
    {
        $yoil = array('일', '월', '화', '수', '목', '금', '토');
        return $yoil[date('w', strtotime($date))];
    }
}

// device check
if (!function_exists('isDevice')) {
    function isDevice()
    {
        $agent = new \Jenssegers\Agent\Agent;

        if ($agent->isDesktop()) {
            return "P";
        }

        if ($agent->isTablet()) {
            return "T";
        }

        return "M";
    }
}

// mobile check
if (!function_exists('isMobile')) {
    function isMobile(): bool
    {
        $agent = new \Jenssegers\Agent\Agent;
        return ($agent->isMobile() || $agent->isTablet());
    }
}

if (!function_exists('masterIp')) {
    function masterIp(): bool
    {
        return in_array(request()->ip(), config('site.app.masterIp'));
    }
}

if (!function_exists('isDebug')) {
    function isDebug(): bool
    {
        return in_array(request()->ip(), config('site.app.debugIp'));
    }
}

if (!function_exists('isDev')) {
    function isDev(): bool
    {
        return in_array(request()->ip(), config('site.app.devIp'));
    }
}

if (!function_exists('customDump')) {
    /**
     * @return never
     */
    function customDump(...$vars)
    {
        if (!in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        foreach ($vars as $v) {
            \Symfony\Component\VarDumper\VarDumper::dump($v);
        }
    }
}
