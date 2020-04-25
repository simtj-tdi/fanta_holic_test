<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Azure Batch configuration
     |--------------------------------------------------------------------------
     */

    'batch' => [
        'url' => env('AZURE_BATCH_URL', ''),
        'key' => env('AZURE_BATCH_KEY', ''),
        'account' => env('AZURE_BATCH_ACCOUNT', ''),
        'api_version' => env('AZURE_BATCH_API_VERSION', ''),
//         'pool_id'   =>  [        // 배열이어야함 수정시 instagramBatchCommand.php 수정필요
//             'pinxyPool-20190930-v1',
//             'pinxyPool-20190930-v2',
//             'pinxyPool-20190930-v3',
// //            'pinxyPool-20190930-v4',  // selenium error
//             'pinxyPool-20190930-v5',
// //            'pinxyPool-20190930-v6', // selenium error
//             'pinxyPool-20190930-v7',
// //            'pinxyPool-20190930-v8',  //killed
//             'pinxyPool-20190930-v9',
// //            'pinxyPool-20190930-v10'  // selenium error
//             'pinxyPool-20191007-v1',
//             'pinxyPool-20191007-v2',
//             'pinxyPool-20191007-v3',
//             'pinxyPool-20191007-v4',
//             ],
        'max_works_per_task' => env('AZURE_BATCH_MAX_WORKS_PER_TASK', 1),
        'max_cores' => env('AZURE_BATCH_MAX_CORES', 20),
    ],
];
