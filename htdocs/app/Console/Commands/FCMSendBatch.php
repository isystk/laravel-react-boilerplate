<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FCMSendBatch extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'fcmsend';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'FCMでPush通知を送信します。';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    Log::info('FCMSendBatch START');
    //

    $this->sendNotification();

    Log::info('FCMSendBatch END');
  }

  /**
   * Write code on Method
   *
   * @return response()
   */
  public function sendNotification()
  {
    // $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
    $firebaseToken = [
      'dlOB13gb4CO6mWn082m2lb:APA91bHs5zxt9RzMvZQi6H2ptZ33Dpq6ReHA1JEVy6WIim3BOD9izzLijRBiqVaFXfnR25BjgJzKjmGjw0i5PtE8VZmXWSkkuIv2t0T_OZ6XDYiEfvGXFgvDtGCr5v16aY0gLJan2cRO'
    ];

    $SERVER_API_KEY = 'AAAAe3tJF6U:APA91bGaxsW9EHtSAkPq4dYqQSRFBwcc_NjMag84oob-EK9zEk426lFk9Sa4BhyvPrgjB-Zp64uxlLdiM3mCOY3q2NLV9quy_hTFOWntGfYQAvxcaDLCZCduiyKFkbBGvLPgRBKMdR_P';

    $data = [
      "registration_ids" => $firebaseToken,
      "notification" => [
        "title" => 'お知らせ',
        "body" => '只今、システムのメンテナンス中です。ご迷惑をおかけしますが暫くお待ち下さい。',
      ]
    ];
    $dataString = json_encode($data);

    $headers = [
      'Authorization: key=' . $SERVER_API_KEY,
      'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    dd($response);
  }
}
