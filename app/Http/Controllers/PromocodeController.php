<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use App\Models\PromocodeClick;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function index(Request $request)
    {
        $promocodes = Promocode::orderByRaw(
            "CASE WHEN date_to > ? THEN 0 ELSE 1 END",
            [Carbon::now()]
        )
        ->orderBy('id', 'desc') // Сортировка по ID в убывающем порядке
        ->paginate(20);

        $page_number = $request['page'];
        $seo_robots = null;
        $seo_canonical = null;
        if ($page_number !== null) {
            $seo_robots = 'noindex, follow';
            $seo_canonical = route('front.promocodes.index');
        }

        return view('areas.front.promocodes.index', [
            'promocodes' => $promocodes,
            'seo_title' => 'Эксклюзивные промокоды и купоны – скидки до 70%',
            'seo_description' => 'Получите эксклюзивные промокоды и купоны для скидок до 70% на лучшие товары и бренды. Не упустите выгодные предложения и экономьте на покупках прямо сейчас',
            'seo_keywords' => 'AliExpress, промокоды, купоны, скидки, эксклюзивные промокоды, акционные коды, акции, распродажа, специальные предложения',
            'page_number' => $page_number,
            'seo_robots' => $seo_robots,
            'seo_canonical' => $seo_canonical,
        ]);
    }

    public function show()
    {
        //
    }

    public function away(Promocode $promocode, Request $request)
    {
        //$click = new PromocodeClick();
/*
        if (!$promocode) {
            return redirect()->back();
        }

        $click->promocode_id = $promocode->id;
        $click->user_ip = getUserIpAddr();
        $click->user_agent = $request->header('User-Agent');

        /*
        $session = $request->hasSession() ? $request->session() : null;

        $click->utm_source   = $session?->get('utm_source');
        $click->utm_medium   = $session?->get('utm_medium');
        $click->utm_campaign = $session?->get('utm_campaign');
        $click->utm_content  = $session?->get('utm_content');
        $click->utm_term     = $session?->get('utm_term');
        $click->referer      = $session?->get('referer');
        $click->erid         = $session?->get('erid');
        $click->user_ulid    = $session?->get('user_ulid');
        */

        //$click->save();

        return redirect($promocode->url);
    }



    public function indexAdmin()
    {
        $promocodes = Promocode::orderBy('id', 'desc')->paginate(10);

        return view('areas.admin.promocodes.index', [
            'promocodes' => $promocodes,
        ]);
    }

    public function create()
    {
        return view('areas.admin.promocodes.create');
    }

    public function store(Request $request)
    {
        $promocode = new Promocode();
        $promocode->fill($request->all());
        $promocode->save();

        return redirect()->route('admin.promocodes.index');
    }

    public function edit(Promocode $promocode)
    {
        return view('areas.admin.promocodes.edit',[
            'promocode' => $promocode
        ]);
    }

    public function update(Promocode $promocode, Request $request)
    {
        $promocode->fill($request->all());
        $promocode->save();
        return redirect()->route('admin.promocodes.index');
    }

    public function disable()
    {
        //
    }

    public function remove()
    {
        //
    }
}
