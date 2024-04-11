<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Member;
class MemberController extends Controller
{
    public function index()
    {

        $members = auth()->user()->customer->member;

        return view('member.member_listing', compact('members'));
    }

    public function show($id)
    {
        $member = Member::find($id);

        $customer_id = auth()->user()->customer_id;

        if ($member->customer_id !== $customer_id) {
            abort(403, 'Unauthorized');
        }
        $orders = $member->orders()->with('products')->get();

        $creditlogs = $member->creditLogs;
    
        return view('member.member_details', compact('member','orders','creditlogs'));
    }
}
