<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BackgroundEMAIL implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $data;
    protected $templateName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($templateName, $data) {
        //
        $this->templateName = $templateName;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
//        $userChatData = \App\UserChat::whereId($this->idd)->first();
////        \App\Http\Controllers\API\ApiController::pushNotification(['title' => 'Message from ' . $userChatData->sender_name, 'body' => $userChatData->message], $userChatData->receiver_id);
//
//        \App\Http\Controllers\API\ApiController::$_AuthId = $userChatData->sender_id;
//        \App\Http\Controllers\API\ApiController::pushNotifications(['title' => 'Message from ' . $userChatData->sender_name, 'body' => $userChatData->message, 'data' => ['target_id' => $userChatData->sender_id, 'target_model' => 'Message']], $userChatData->receiver_id, true);
        $data = $this->data;
        \Mail::send('emails.' . $this->templateName, $data, function($message) use ($data) {
            $message->to($data['to']);
            $message->subject($data['subject']);
        });
    }

}
