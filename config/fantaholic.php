<?php
return[
// App
    'version'=>[
        'and'=>[
            'app_ver' => '1.0.0',   // 앱 최신 버전
            'forced_update' => 'auto',   // 앱 강제 업데이트 여부 (forced / auto)
            'inspection'    =>  false
        ],
        'ios'=>[
            'app_ver' => '1.0.1',   // 앱 최신 버전
            'forced_update' => 'forced',   // 앱 강제 업데이트 여부 (forced / auto)
            'inspection'    =>  false
        ]
    ]
];
