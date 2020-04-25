<?php

namespace App\Http\Traits;

trait PushTrait
{
    /**
     * fcm authorization key
     *
     * @var string
     */
    //protected $key = 'AAAAvM-bt7Q:APA91bHhGCkRhZ3GOC1eegjvww6hLUpZhfdz9c8YwqMoTR_BIJrrB06Wr8azPJUzl6AilfsmlDtQK5wjFrJMABW0jgj4V1BKB6Tq4vT_Cn5bFwEfKVJoHqZr508ZVfUurG9glnFcX6H1'; 20200109 이전
    //protected $key = 'AAAAV7knT5g:APA91bGK1YlgsXe1Qt5jGTFRHPh_2fufOo45rzdGN9CYm_CRIVz3meEKjkkUbVaNqJJ4_pFBRNizW_lp9ub5OuuoGFU8yAfanEXVOHjyRdZjZhbuPgxynhW6md_mnKiOWpN8FTe8WjH3';

    /**
     * fcm send to url
     *
     * @var string
     */
    protected $url = 'https://fcm.googleapis.com/fcm/send';

    /**
     * fcm send headers
     *
     * @return array
     */
    protected function getHeaders($app)
    {
        $this->key = config('celeb')[$app]['fcm_server_key'];

        return [
            'Authorization: key=' . $this->key,
            'Content-Type: application/json'
        ];
    }

    /**
     * fcm sender
     *
     * @param $items
     * @return array
     */
    public function sender($app, $items)
    {
        $multiHandle = curl_multi_init();

        $handles = [];
        foreach ($items as $id => $item) {
            $handles[$id] = curl_init();

            curl_setopt($handles[$id], CURLOPT_URL, $this->url);
            curl_setopt($handles[$id], CURLOPT_POST, true);
            curl_setopt($handles[$id], CURLOPT_HTTPHEADER, $this->getHeaders($app));
            curl_setopt($handles[$id], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handles[$id], CURLOPT_POSTFIELDS, json_encode($item));

            curl_multi_add_handle($multiHandle, $handles[$id]);
        }

        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
        } while($running > 0);

        $results = [];
        foreach($items as $id => $item) {
            $results[$id] = json_decode(curl_multi_getcontent($handles[$id]), true);

            curl_multi_remove_handle($multiHandle, $handles[$id]);
        }

        curl_multi_close($multiHandle);

        return $results;
    }
}
