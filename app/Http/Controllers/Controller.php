<?php

namespace App\Http\Controllers;

use App\Services\CommonServices;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $data = [];

    protected $CommonServices;

    public function __construct()
    {
        $this->CommonServices = (new CommonServices());
    }

    public function tinyUpload(Request $request)
    {
        return [
            'location' => $this->CommonServices->fileUploadService($request->file('file'), '/tinymce')['realfile'],
        ];
    }

    public function plUpload(Request $request)
    {
        return $this->CommonServices->fileUploadService($request->file('file'), $request->directory);
    }

    public function fileDownload(Request $request)
    {
        return ($request->type === 'only')
            ? $this->CommonServices->fileDownloadService($request)
            : $this->CommonServices->zipDownloadService($request);
    }

    public function captchaMake(Request $request)
    {
        return $this->CommonServices->captchaMakeService();
    }
}