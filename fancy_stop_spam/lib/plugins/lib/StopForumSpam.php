<?php

class FancyStopSpamStopForumSpam
{
    const API_URL = 'http://www.stopforumspam.com/api?';
    private $apiKey;

    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    public function request(array $data)
    {
        $result = FALSE;

        if (function_exists('json_decode')) {
            $data['f'] = 'json';
        } else {
            $data['f'] = 'serial';
        }

        $data['unix'] = '1';

        $check_url = self::API_URL . http_build_query($data);
        $response = get_remote_file($check_url, 20, FALSE, 2);

        if (isset($response['content']) !== FALSE && !empty($response['content'])) {
            if ($data['f'] == 'json') {
                $result = json_decode($response['content'], TRUE);
            } else {
                $result = unserialize($response['content']);
            }
        }

        return $result;
    }

    public function isSuccessfullResponse($response)
    {
        return (is_array($response) && isset($response['success']) && $response['success'] == '1');
    }
}
