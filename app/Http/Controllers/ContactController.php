<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        $contact = [
            'address'    => Setting::get(Setting::CONTACT_ADDRESS),
            'email'      => Setting::get(Setting::CONTACT_EMAIL),
            'phone'      => Setting::get(Setting::CONTACT_PHONE),
            'hours'      => Setting::get(Setting::CONTACT_HOURS),
            'maps_embed' => Setting::get(Setting::CONTACT_MAPS_EMBED),
        ];

        return view('contact', compact('contact'));
    }
}
