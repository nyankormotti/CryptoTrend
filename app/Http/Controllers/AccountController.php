<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request) {

        // $limit = $request->page * 20;
        // $offset = $limit - 20;

        $account_list = Account::where('user_id',Auth::id())->where('follow_flg', $request->follow_flg)->orderBy('last_updated','DESC')->get();
        // var_dump($account_list[0]);
        // var_dump($account_list[0]);
        // var_dump($account_list[0]['last_updated']);
        // var_dump($account_list[0]['follow_flg']);
        // var_dump($account_list[0]['account_name']);exit;

        return $account_list;
    }

    public function totalCount(Request $request) {
        $account_count = count(Account::where('user_id', Auth::id())->where('follow_flg', $request->follow_flg)->get());

        return $account_count;
    }
}
