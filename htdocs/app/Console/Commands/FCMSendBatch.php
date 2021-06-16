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
      'eku7wKsNTZjoVFzEiF7vjs:APA91bF8e7LDSHKShEFj1u7w3t9SxwlWiEU92AXGGUYx7i5xqalXRzqOIXGgoScSgx0XPCnnOBeTNPCs7dbxGjWGs2FAY-7eBH8DI2REKd66wBvKnQu-WE3QkbS_HqnPchhk0z8LqdDS'
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
