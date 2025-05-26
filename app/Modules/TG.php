<?php

namespace App\Modules;

class TG
{
    private $token = null;

    public function __construct()
    {
        $this->token = config('__.TG.token');
    }


    function imagePost($chat, $file, $text)
    {
        $ch = curl_init();

        $cFile = curl_file_create($file);

        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token .'/sendPhoto');
        $post = [
            'chat_id' => $chat,
            'photo'   => $cFile,
            'caption' => $text,
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = curl_exec($ch);
        curl_close($ch);
    }

    function filePost($chat, $file, $text)
    {
        $ch = curl_init();

        $cFile = curl_file_create($file);

        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token .'/sendDocument');
        $post = [
            'chat_id' => $chat,
            'document'   => $cFile,
            'title' => $text,
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = curl_exec($ch);
        curl_close($ch);
    }

    function textPost($chat, $text)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token .'/sendMessage');
        $post = [
            'chat_id' => $chat,
            'text' => $text,
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = curl_exec($ch);
        curl_close($ch);
    }

    function sendAlbum($chat, $images, $text, $link = null)
    {
        $ch = curl_init();

        $files = [];
        $pathes = [];
        $i = 0;
        foreach ($images as $image) {
            $i++;
            $fileName = basename($image);
            $files[] = [
                'type' => 'photo',
                'media' => 'attach://' . $fileName,
            ];
            $pathes[$fileName] = curl_file_create($image);
        }
        $files[0]['caption'] = $text;
        $files[0]['parse_mode'] = 'HTML';

        $post = [
            'chat_id' => $chat,
            'media' => json_encode($files),
        ];

        $post = array_merge($post, $pathes);

        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token.'/sendMediaGroup');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = curl_exec($ch);

        curl_close($ch);
    }

    function sendPhotoPost($chat, $text, $image, $url)
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Купить в Aliexpress', 'url' => $url],
                ],
            ],
        ];

        $post = [
            'chat_id' => $chat,
            'photo' => curl_file_create($image,  mime_content_type($image), basename($image)),
            'caption' => $text,
            'reply_markup' => json_encode($keyboard),
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token.'/sendPhoto');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $res = curl_exec($ch);

        if ($res === false) {
            dd([
                'curl_error' => curl_error($ch),
                'curl_errno' => curl_errno($ch)
            ]);
        }

        curl_close($ch);
    }
}