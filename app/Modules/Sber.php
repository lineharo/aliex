<?php

namespace App\Modules;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Sber
{
    const URL_TOKEN = 'https://ngw.devices.sberbank.ru:9443/api/v2/oauth';
    private $fileToken = null;
    private $token = null;

    public function __construct()
    {
        $this->fileToken = storage_path('modules/sber/token.json');

        if (file_exists($this->fileToken)) {
            $this->token = json_decode(file_get_contents($this->fileToken))->access_token;
        }
    }

    public function checkAuth()
    {
        $res = Http
            ::withToken($this->token)
            ->withOptions(['verify' => false])
            ->get('https://gigachat.devices.sberbank.ru/api/v1/models');
        return $res->status() == 200;
    }

    public function getToken()
    {
        $res = Http
            ::asForm()
            ->withToken(config('__.SBER.credentials'))
            ->withOptions(['verify' => false])
            ->withHeaders([
                'RqUID' => Str::uuid()->toString(),
            ])
            ->post(self::URL_TOKEN, [
                'scope' => 'GIGACHAT_API_PERS',
            ]);

        if ($res->status() != 200) return null;

        $token = $res->body();
        file_put_contents($this->fileToken, $token);

        return true;
    }

    public function makeDescription($prompt)
    {
        $data = [
            "model" => "GigaChat:latest",
            "temperature" => 0.87,
            "top_p" => 0.47,
            "n" => 1,
            "max_tokens" => 1200,
            "repetition_penalty" => 1.07,
            "stream" => false,
            "update_interval" => 0,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Отвечай как профессиональный SEO специалист",
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ],
            ],
        ];

        $res = Http
            ::withToken($this->token)
            ->withOptions(['verify' => false])
            ->acceptJson()
            ->withBody(json_encode($data))
            ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions');

        if ($res->status() != 200) return null;

        $description = json_decode($res->body(), false)->choices[0]->message->content;

        return $description;

    }

    public function makeTitle($prompt)
    {
        $data = [
            "model" => "GigaChat:latest",
            "temperature" => 1.3 + (1/rand(1, 99) / 100),
            "top_p" => 0.01,
            "n" => 1,
            "max_tokens" => 350,
            "repetition_penalty" => 1.4,
            "stream" => false,
            "update_interval" => 0,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Отвечай как профессиональный интернет маркетолог и SEO специалист",
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ],
            ],
        ];

        $res = Http
            ::withToken($this->token)
            ->withOptions(['verify' => false])
            ->acceptJson()
            ->withBody(json_encode($data))
            ->post('https://gigachat.devices.sberbank.ru/api/v1/chat/completions');

        if ($res->status() != 200) return null;

        $description = json_decode($res->body(), false)->choices[0]->message->content;

        return $description;

    }
}