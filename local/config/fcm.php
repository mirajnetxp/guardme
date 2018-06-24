<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => true,

    'http' => [
        'server_key' => 'AAAAH7De3jM:APA91bEYiCZr0dNNPX4JKBRg_SNrfkO-Mm_EygYjh8fIqBzs7nPBeRUonRQQCfzCmfg7f9G6PlYPBWc2veJlse1N4fvUR3oy48r36-zSPygKyAZPnvAuOKNGkbxfxPOmec4fbyjqj2C8NErD_fiIwLOpB-UUvo_nsA',
        'sender_id' => '136111382067',
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
