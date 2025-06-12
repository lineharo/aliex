<?php

namespace App\Modules;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Gemini
{
    const API_KEY = 'AIzaSyBIrAiJZdEquX0gsYXbUDpIaF8nvb8GRpg';
    const PROXY = 'http://zYA0aK:Sy2zJk@45.91.209.157:12008';

    public function makeDescription($prompt)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . self::API_KEY;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withOptions([
                'proxy' => self::PROXY,
                'timeout' => 60,
                'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]
            ])
            ->post($url, [
                'system_instruction' => [
                    'parts' => [
                        'text' => <<<SYS
Ты профессиональный SEO-копирайтер, создающий описания для интернет-магазина.

Твоя задача — писать тексты, которые:
• хорошо индексируются поисковиками;
• включают ключевые слова естественным образом;
• подробно и честно рассказывают о товаре;
• не используют клише, лозунги и фразы в стиле маркетинга 2010-х годов.

Правила:
- Пиши от третьего лица, без "мы", "вы", "представляем".
- Не приводи примеры отзывов, а делай саммари.
- Не приводи примеры характеристик, а делай саммари.
- Добавь пару советов по использованию.
- Если есть смысл, то добавить самые распространённые вопросы и ответы на них (3-5).
- Не используй призывы к действию.
- Оформляй текст только в <p> и <ul class="list-st"><li>.
- Минимальный объём — 3000 символов.
- Не вставляй заглушки ("вставьте сюда", "укажите", и т.д.).
- Будь профессионален, но не робот — допускается живой язык.
SYS
                    ],
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig'=> [
                    'stopSequences' => '[]',
                    'temperature' => 1.2,
                    'maxOutputTokens' => rand(1200, 2500),
                    'topP' => 0.9,
                    'topK' => 5,
                ],
            ]);

            $responseData = json_decode($response->body(), true);
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $responseData['candidates'][0]['content']['parts'][0]['text'];
                return $text;
            }
        } catch (\Exception $e) {
            // log
        }

        return null;
    }

    public function makeTitle($prompt)
    {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . self::API_KEY;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withOptions([
                'proxy' => self::PROXY,
                'timeout' => 60,
                'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]
            ])
            ->post($url, [
                'system_instruction' => [
                    'parts' => [
                        'text' => 'Ты профессиональный SEO-специалист и пишешь тексты для сайтов с учётом ключевых слов и всех правил. Тебе нужно написать название для товара на сайте. Отправь только одно финальное название.'
                    ],
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig'=> [
                    'stopSequences' => '["###"]',
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1000,
                    'topP' => 1,
                    'topK' => 25,
                ],
            ]);

            $responseData = json_decode($response->body(), true);
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $responseData['candidates'][0]['content']['parts'][0]['text'];
                return $text;
            }

        } catch (\Exception $e) {
            // log
        }

        return null;
    }
}