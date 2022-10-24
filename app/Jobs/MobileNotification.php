<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MobileNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = [
            "to" => "/topics/" . $this->data['topic'],
            "priority" => "high",
            "notification" => [
                "title" => $this->data['title'],
                "body" => $this->data['body']
            ]
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAAuQ5bxZQ:APA91bGdV8kosO9uA3JMzYb5_fQZjB8Edg97ls3eCdt21vClKTJ3zwhS9Xbt4u2LtDbXfTtGHtJwLOyETXDTXl2jLBrnqs-rVQsiklXj-ndttIlRVp_DbjumreIiMXYMjLqEQ7BJSq0B';
        $headers = array(
            'Content-Type: application/json',
            'Authorization: key=' . $api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        curl_exec($ch);
        curl_close($ch);
    }
}
