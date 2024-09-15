<?php

namespace App\Services;

use App\Models\QueryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class Services
 * @package App\Services
 */
class AppServices
{
    protected $data = [];
    protected $jsonData = [];

    protected function enableQueryLog(): void
    {
        DB::enableQueryLog();
    }

    protected function getQuery(): array
    {
        foreach (DB::getQueryLog() ?? [] as $query) {
            $sql = $query['query'];
            $bindings = $query['bindings'];

            $queryResult[] = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        }

        return $queryResult ?? [];
    }

    protected function transaction(): void
    {
        DB::beginTransaction();
        $this->enableQueryLog();
    }

    protected function dbCommit(string $subject, string $msg = ''): void
    {
        DB::commit();

        if(!empty($msg)) {
            setFlashData(['msg' => $msg]);
        }

        QueryLog::create([
            'u_sid' => thisPK(),
            'subject' => $subject,
            'query' => $this->getQuery(),
            'ip' => request()->ip(),
        ]);
    }

    protected function dbRollback($error, $stop = false)
    {
        DB::rollback();

        $errorInfo = [
            'Message' => $error->getMessage(),
            'Code' => $error->getCode(),
            'File' => $error->getFile(),
            'Line' => $error->getLine(),
            'Query' => $this->getQuery(),
            'Trace' => $error->getTrace(),
        ];

        if ($stop || isDev()) {
            dd($errorInfo);
        }

        Log::channel('dbError')->error("================================== DB ERROR ===================================");
        foreach($errorInfo as $key => $val) {
            if ($key == 'Trace') {
                continue;
            }

            if (is_array($val)) {
                foreach ($val as $index => $item) {
                    Log::channel('dbError')->error("ERROR {$key}: " . $item);
                }
            } else {
                Log::channel('dbError')->error("ERROR {$key}: " . $val);
            }
        }
        Log::channel('dbError')->error("===============================================================================");

        return dbRedirect();
    }

    protected function setJsonData($data, $value = ''): void
    {
        if (!is_array($data)) {
            $this->jsonData[$data] = $value;
        } else {
            foreach ($data as $key => $val) {
                $this->jsonData[$key] = $val;
            }
        }
    }

    protected function returnJson(): array
    {
        return $this->jsonData;
    }

    protected function returnJsonData($data, $value): array
    {
        $this->setJsonData($data, $value);
        return $this->returnJson();
    }

    protected function ajaxActionHtml(string $selector, string $html)
    {
        return ['selector' => $selector, 'html' => $html];
    }

    protected function ajaxActionCss(string $selector, string $css, string $val = '')
    {
        return ['selector' => $selector, 'css' => $css, 'val' => $val];
    }

    protected function ajaxActionClass(string $selector, string $class)
    {
        return ['selector' => $selector, 'class' => $class];
    }

    protected function ajaxActionInput(string $selector, string $input)
    {
        return ['selector' => $selector, 'input' => $input];
    }

    protected function ajaxActionText(string $selector, string $text)
    {
        return ['selector' => $selector, 'text' => $text];
    }

    protected function ajaxActionAttr(string $selector, string $attr, string $val)
    {
        return ['selector' => $selector, 'attr' => $attr, 'val' => $val];
    }

    protected function ajaxActionData(string $selector, string $name, string $data)
    {
        return ['selector' => $selector, 'name' => $name, 'data' => $data];
    }

    protected function ajaxActionTrigger(string $selector, string $event)
    {
        return ['selector' => $selector, 'event' => $event];
    }

    protected function ajaxActionProp(string $selector, string $event, bool $val)
    {
        return ['selector' => $selector, 'event' => $event, 'val' => $val];
    }

    protected function ajaxActionRemove(string $selector)
    {
        return ['selector' => $selector];
    }

    protected function ajaxActionLocation(string $case, string $url = '')
    {
        return ['case' => $case, 'url' => $url];
    }

    protected function ajaxActionWinClose(bool $bool = false)
    {
        return ['reload' => $bool];
    }
}