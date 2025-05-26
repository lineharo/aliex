<?php

namespace App\Console\Commands;

use App\Models\Promocode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class aliexLoadPromo extends Command
{
    protected $signature = 'aliex:loadPromo';
    protected $description = 'Загрузить промокоды с Admitad';

    public function handle()
    {
        // URL файла XML
        $url = 'https://export.admitad.com/ru/webmaster/websites/2312961/coupons/export/?website=2312961&advcampaigns=25179&code=t9vbjncddm&user=lineharo&region=00&format=xml&v=1';

        // Скачивание файла
        $response = Http::get($url);

        if ($response->ok()) {

            // Парсинг XML
            $xml = simplexml_load_string($response->body());

            foreach ($xml->coupons->coupon as $coupon) {

                // Извлечение данных
                $admitadId = $coupon['id'];

                // Проверка существования записи по admitad_id
                if (Promocode::where('admitad_id', $admitadId)->exists()) {
                    continue; // Пропустить запись, если такая уже существует
                }

                $name = (string) $coupon->name;

                $discountRaw = (string) $coupon->discount; // Исходное значение скидки
                $parsedDiscount = $this->parseDiscount($discountRaw); // Разбиваем скидку
                $offerAmount = $parsedDiscount['amount']; // Сумма скидки
                $offerCurrency = $parsedDiscount['currency']; // Валюта или знак
                $promocode = (string)$coupon->promocode === 'Not required' ? null : (string)$coupon->promocode;
                $storeName = null;
                $dateFrom = Carbon::parse((string) $coupon->date_start);
                $dateTo = Carbon::parse((string) $coupon->date_end);
                $url = (string) $coupon->gotolink;

                // Сохранение в БД
                $promo = Promocode::create([
                    'code' => $promocode,
                    'admitad_id' => $admitadId,
                    'name' => $name,
                    'offer_amount' => $offerAmount,
                    'offer_currency' => $offerCurrency,
                    'store_name' => $storeName,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'url' => $url,
                ]);

            }

            return 0;
        } else {
            return 500;
        }
    }

    private function parseDiscount(string $discount): array
    {
        // Регулярное выражение для разбора скидки
        preg_match('/([\d\s,.]+)(.*)/u', $discount, $matches);

        // Приведение суммы к числовому формату
        $amount = isset($matches[1]) ? trim(str_replace([' ', ','], ['', '.'], $matches[1])) : null;
        $currency = isset($matches[2]) ? trim($matches[2]) : null;

        return [
            'amount' => $amount,
            'currency' => $currency,
        ];
    }
}
