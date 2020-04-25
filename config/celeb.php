<?php

return [

    'fantaholic' => [
        // CDN 주소
        'cdn' => 'https://fantaholic-cdn.azureedge.net/',
        // FCM 서버키
        'fcm_server_key'   => 'AAAATJjXjeU:APA91bH0Bz-U7xW5gTMzLBSf-ItB1feb0kCjtAiytLM3LAWu08_fe5Mc8de0w4ZiVYJS4FrJn9yGY-dl4yQ_MCaeHGwMNKfn1P6s69iQYm-f3X1Rts2aEW0Q5xUlIfhcahKdHof4LDPP',
        //
        'curl_url' => 'http://devtrs.ce1eb.com',
        //구글 마켓 공유
        'shared_url'    => 'https://play.google.com/store/apps/details?id=com.curation.fantaholic',

        'app_name' => 'fantaholic',        //앱 이름

        'celeb_name' => 'fantaholic',         //셀럽 이름
        'artist_name'    => '',
        'youtube_channel' => 'UCEOA5dqY5Qzz5MaKBImavYg',
        //WECHAT
        'wechat_app_id' =>  env('KRIESHACHU_WECHAT_APP_ID'),
        'wechat_secret' =>  env('KRIESHACHU_WECHAT_SECRET'),
        'inspection'    =>  false
    ],
];
