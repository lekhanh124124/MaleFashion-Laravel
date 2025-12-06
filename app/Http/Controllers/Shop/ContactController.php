<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;

class ContactController extends BaseController
{
    public function store(Request $request)
    {
        $userId = session('customer_user_id');
        $user = $userId ? User::find($userId) : null;

        $data = $request->validate([
            'NAME' => 'required|string|max:255',
            'EMAIL' => 'required|email|max:255',
            'MESSAGE' => 'required|string|max:2000',
        ]);

        Contact::create([
            'USER_ID' => $userId,
            'NAME' => $data['NAME'],
            'EMAIL' => $data['EMAIL'],
            'MESSAGE' => $data['MESSAGE'],
            'STATUS' => 'new',
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
    }
}