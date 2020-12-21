<?php

namespace modules\analysis\common\services;

use GuzzleHttp\Client;
use yii\base\Component;

class AnalyserApiService extends Component
{
    /**
     * @var Client
     */
    private $client;

    public function init()
    {
        parent::init();
        $this->client = new Client(['base_uri' => \Yii::$app->params['UprzaKernelHost']]);
    }

    public function createTextAnalyseMorf(string $text)
    {
        $response = $this->client->post(
            '/analyse/synt',
            [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => '{"text":"' . $text . '"}'
            ]
        );

        return json_decode($response->getBody()->getContents());
    }
}
