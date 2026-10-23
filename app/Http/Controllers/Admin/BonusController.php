<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BonusRequest;
use App\Models\RegisterBonus;
use Illuminate\Support\Facades\Validator;

class BonusController extends Controller
{
    public function index()
    {
      return view('admin.user.bonus');
    }

    public function update(BonusRequest $request)
    {
        $bonus= RegisterBonus::first();
        $data = $request->all();
        $bonus->update($data);

        $msg = 'Bonus Updated Successfully.';
        return response()->json($msg);
    }

}
