<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardFile;
use App\Models\MailFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class DefaultServices
 * @package App\Services
 */
class CommonServices extends AppServices
{
    private function filenameRegx(string $filename): string
    {
        // 파일명에 허용되지않는 특수문자 제거
        return preg_replace("/[ #\&\+\-%@=\/\\\:;,\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", ' ', $filename);
    }

    public function fileUploadService($file, string $folder)
    {
        $directory = "uploads/" . $folder;

        $ext = $file->getClientOriginalExtension();
        $save_name = (now()->timestamp . '_' . Str::random(10) . '.' . $ext);

        return [
            'filename' => $this->filenameRegx($file->getClientOriginalName()),
            'realfile' => '/storage/' . $file->storeAs($directory, $save_name, 'public')
        ];
    }

    public function fileDownloadService(Request $request)

    {
        $tbl = $request->tbl;
        $sid = deCryptString($request->sid);

        switch ($tbl) {
            case 'board':
                $field = $request->field;
                $isThumbnail = ($field == 'thumbnail');
                $pathField = ($isThumbnail) ? 'thumbnail_realfile' : "realfile{$field}";
                $nameField = ($isThumbnail) ? 'thumbnail_filename' : "filename{$field}";
                $downloadField = ($isThumbnail) ? 'thumbnail_download' : "file{$field}_download";

                $board = Board::findOrFail($sid);
                $board->increment($downloadField);

                $this->data = ['realfile' => $board->{$pathField}, 'filename' => $board->{$nameField}];
                break;

            case 'boardFile':
                $boardFile = BoardFile::findOrFail($sid);
                $boardFile->increment('download');

                $this->data = ['realfile' => $boardFile->realfile, 'filename' => $boardFile->filename];
                break;

            default:
                return notFoundRedirect();
        }

        return (File::exists(public_path($this->data['realfile'])))
            ? response()->download(public_path($this->data['realfile']), $this->data['filename'])
            : errorRedirect('back', errorMsg('nFile')); // 파일 데이터가 없을경우
    }

    public function zipDownloadService(Request $request)
    {
        $tbl = $request->tbl;
        $sid = deCryptString($request->sid);

        switch ($tbl) {
            case 'board': // 게시판 pluplad 파일 일괄 다운로드
                $board = Board::findOrFail($sid);
                $board->files()->increment('download');

                $this->data = $this->makeZip("{$board->subject}.zip", $board->files, $request->password ?? null);
                break;

            default:
                return notFoundRedirect();
        }


        return (File::exists($this->data['realfile']))
            ? response()->download($this->data['realfile'], $this->data ['filename'])->deleteFileAfterSend(true)
            : errorRedirect('back', errorMsg('nFile')); // 파일 데이터가 없을경우
    }

    public function fileDeleteService(string $realfile)
    {
        if (File::exists(public_path($realfile))) {
            File::delete(public_path($realfile));
        }
    }

    public function makeZip($filename, $fileData, $password = null)
    {
        // Zip 파일을 저장할 디렉터리 경로
        $zipDirectory = storage_path('app/zipArchive');

        // 비밀번호가 있을경우
        if ($password) {
            $password = deCryptString($password);
        }

        // 폴더가 없을경우 생성
        if (!File::exists($zipDirectory)) {
            File::makeDirectory($zipDirectory, 0755, true);
        }

        // 특수문자 제거한 압축 파일명
        $zipFile['filename'] = $this->filenameRegx($filename);

        // 압축 파일 경로
        $zipFile['realfile'] = "{$zipDirectory}/{$zipFile['filename']}";

        // ZipArchive 인스턴스 생성
        $zip = new \ZipArchive();

        // zip 아카이브 생성 여부 확인
        if ($zip->open($zipFile['realfile'], \ZipArchive::CREATE) !== true) {
            return serverRedirect();
        }

        // 비밀 번호 있을경우 암호 설정
        if ($password) {
            $zip->setPassword($this->zipPassword());
        }

        // addFile ( 파일이 존재하는 경로, 저장될 이름 )
        foreach ($fileData ?? [] as $row) {
            $path = public_path($row->realfile);

            // 파일 있다면 추가
            if (File::exists($path)) {
                $zip->addFile($path, $row->filename);

                // 비밀번호 있을경우 암호화
                if ($password) {
                    $zip->setEncryptionName($path, \ZipArchive::EM_AES_256);
                }
            }
        }

        $zip->close();

        return $zipFile;
    }

    public function excelDownload($object, $filename)
    {
        return Excel::download($object, "{$filename}.xlsx");
    }

    public function captchaMakeService()
    {
        // 기존 캡차 지우기
        session()->forget('captcha');

        // 이미지 크기
        $img = imagecreate(115, 40);

        // 캡챠 폰트 크기
        $size = 25;

        // 캡챠 폰트 기울기
        $angle = 0;

        // 캡챠 폰트 x, y위치
        $x = 10;
        $y = 30;

        // 이미지의 바탕화면은 흰색
        $background = imagefill($img, 0, 0, imagecolorallocatealpha($img, 255, 255, 255, 100));

        // 폰트 색상
        $text_color = imagecolorallocate($img, 233, 14, 91);

        // 폰트 위치
        $font = public_path('captcha/fonts/Roboto-Black.ttf');

        // 캡챠 텍스트
        $captchaStr = substr(md5(rand(1, 10000)), 0, 5);

        // 생성된 캡챠 문자열을 세션에 저장
        session()->put('captcha', $captchaStr);

        // 글자를 이미지로 만들기
        imagettftext($img, $size, $angle, $x, $y, $text_color, $font, $captchaStr);

        // 이미지를 base64로 인코딩
        ob_start();

        imagejpeg($img);
        $imageData = ob_get_clean();
        $base64Image = base64_encode($imageData);

        // 이미지 생성 후 리턴
        return 'data:image/jpeg;base64,' . $base64Image;
    }

    public function captchaCheckService(string $captcha = '')
    {
        if ($captcha === session('captcha')) {
            // 기존 캡차 지우기
            session()->forget('captcha');
            return 'suc';
        }

        return $this->returnJsonData('alert', [
            'case' => true,
            'msg' => errorMsg('captcha'),
            'focus' => '#captcha',
            'trigger' => [
                $this->ajaxActionTrigger('.captcha img.refresh', 'click')
            ],
        ]);
    }
}