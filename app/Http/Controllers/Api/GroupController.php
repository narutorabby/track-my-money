<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::whereHas('group_users', function($query) use($request) {
            return $query->where('user_id', $request->user()->id);
        })->with('admin')->withCount(['members', 'records'])->get();

        return successResponse("Group list", $groups);
    }

    public function show($slug)
    {
        $group = Group::where('slug', $slug)->with('members:id,name,email,mobile,avatar', 'admin')->withCount(['members', 'records'])->first();
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

            $groupUser = new GroupUser();
            $groupUser->group_id = $group->id;
            $groupUser->user_id = $group->created_by;
            $groupUser->joined_at = Carbon::now();
            $groupUser->save();

            DB::commit();
            return successResponse("Group created successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Group create", ['e' => $e]);
            return errorResponse("Could not create group!");
        }
    }

    public function update(Request $request, $id)
    {
        $group = Group::where('id', $id)->first();
        if($group == null) {
            return errorResponse("Group not found");
        }
        DB::beginTransaction();
        try {
            $group->name = $request->name;
            $group->updated_by = $request->user()->id;
            $group->save();
            DB::commit();
            return successResponse("Group updated successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Group update", ['e' => $e]);
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
