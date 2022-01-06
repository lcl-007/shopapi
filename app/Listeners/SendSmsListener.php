<?php

namespace App\Listeners;
use Overtrue\EasySms\EasySms;
use App\Events\SendSms;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendSms  $event
     * @return void
     */
    public function handle(SendSms $event)
    {
        //发送验证码到手机
$config = config('sms');
$easySms = new EasySms($config);
$code = rand(1000,9999);

//缓存验证码
Cache::put('phone_code_'.$event->phone, $code,now()->addMinutes(15));

try{
$easySms->send($event->phone, [
    'template' => $config['template'],
    'data' => [
        'code' => $code,
        'product'=>$event->product
    ],
]);
}catch(\Exception $e){
    return $e->getExceptions();
}
    }
}
