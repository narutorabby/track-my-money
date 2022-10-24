<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\MobileNotification;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::whereHas('members', function($quesry) use($request) {
            $quesry->user_id = $request->user()->id;
        })->with('admin')->withCount(['members', 'records'])->get();

        return successResponse("Group list", $groups);
    }

    public function show($slug)
    {
        $group = Group::where('slug', $slug)->first();
        if($group) {
            return successResponse("Group details", $group);
        }
        return errorResponse("Group not found");
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $group = new Group();
            $group->name = $request->name;
            $group->slug = Str::slug($group->title) . "-" . md5($request->user()->id) . "-" . round(microtime(true) * 1000);
            $group->created_by = $request->user()->id;
            $group->save();

            $member = new Member();
            $member->group_id = $group->id;
            $member->user_id = $group->created_by;
            $member->joined_at = Carbon::now();
            $member->save();

            DB::commit();
            return successResponse("Group created successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not create group!");
        }
    }

    public function update(Request $request, $id)
    {
        return errorResponse("This feature is not available at this moment!");
        $group = Group::where('id', $id)->first();
        if($group == null) {
            return errorResponse("Group not found");
        }
        DB::beginTransaction();
        try {
            $group = new Group();
            $group->name = $request->name;
            $group->updated_by = $request->user()->id;
            $group->save();
            DB::commit();
            return successResponse("Group updated successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not update group!");
        }
    }

    public function delete(Request $request, $id)
    {
        return errorResponse("This feature is not available at this moment!");
        DB::beginTransaction();
        try {
            $group = Group::where('id', $id)->with('group')->first();
            if($group == null) {
                return errorResponse("Group does not exists!");
            }
            if($group->created_by != $request->user()->id) {
                return errorResponse("You do not have permission to delete this group");
            }

            $group->delete();
            DB::commit();
            return successResponse("Group deleted successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not delete group!");
        }
    }
}
