<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\GroupUser;
use App\Models\Record;
use App\Models\RecordShare;

class MemberController extends Controller
{
    public function list(Request $request)
    {
        $group = Group::where('slug', $request->slug)->first();
        $members = [];
        if($group != null) {
            $members = GroupUser::where('group_id', $group->id)->with('user')->get();
            foreach ($members as $key => $member) {
                $recordIds = Record::where('group_id', $group->id)->pluck('id')->toArray();

                $totalContribution = RecordShare::whereIn('record_id', $recordIds)->where('user_id', $member->user->id)->whereHas('record', function($q){
                    $q->where('type', "Contribution");
                })->sum('share');
                $member->total_contribution = $totalContribution;

                $totalBill = RecordShare::whereIn('record_id', $recordIds)->where('user_id', $member->user->id)->whereHas('record', function($q){
                    $q->where('type', "Bill");
                })->sum('share');
                $member->total_bill = $totalBill;
            }
        }
        return successResponse("Member list", $members);
    }
}
