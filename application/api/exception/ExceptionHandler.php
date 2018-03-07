<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 14:28
 */

namespace app\api\exception;

use think\exception\Handle;
use think\Log;
use think\Request;
use Exception;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            //如果是自定义异常，则不需要记录日志
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        } else {
            //如果打开了调试模式，需要返回系统原生的异常抛出
            if (config('app_debug')) {
                return parent::render($e);
            }

            $this->code = 500;
            $this->msg = 'sorry,it is complicated';
            $this->errorCode = 99999;
            $this->errorLog($e);
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    private function errorLog(Exception $e)
    {
        Log::init([
            'type' => 'file',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}