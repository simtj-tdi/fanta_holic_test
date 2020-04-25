<?php
return[
// xpath
    'instagram' =>[
        'img'=>[
          'xpath' => '//div/img',
        ],
        'vod'=>[
          'xpath' => '//div/video',
        ],
        'version'=> '1.0.0',
        'url' => 'https://www.instagram.com'
    ],
    'vlive' =>[
        'vod'=>[
          'xpath' => 'vlive.video.init,8,9'
        ],
        'version'=> '1.0.0',
        'url' => 'https://www.vlive.tv/'

    ],
    'youtube' =>[
        'vod'=>[
          'xpath' => ''
        ],
        'version'=> '',
        'url' => 'https://www.youtube.com/watch?v='

    ],
    'twitter' =>[
        'vod'=>[
          'xpath' => ''
        ],
        'version'=> '',
        'url' => 'https://twitter.com/'

    ]

];
