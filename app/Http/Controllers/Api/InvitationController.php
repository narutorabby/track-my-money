<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\MobileNotification;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\GroupUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    public function list(Request $request)
    {
        $type = $request->type ?? null;
        $invitations = Invitation::with('group.admin', 'user');
        if($type == "sent") {
            $groupIds = Group::where('created_by', $request->user()->id)->pluck('id')->toArray();
            $invitations = $invitations->whereIn('group_id', $groupIds);
        }
        else if($type == "received") {
            $invitations = $invitations->where('user_id', $request->user()->id);
        }
        else {
            $groupIds = Group::where('created_by', $request->user()->id)->pluck('id')->toArray();
            $invitations = $invitations->where(function ($query) use($groupIds, $request) {
                return $query->whereIn('group_id', $groupIds)->orWhere('user_id', $request->user()->id);
            });
        }
        $invitations = $invitations->orderBy('id', "DESC")->get();
        return successResponse("Invitations", $invitations);
    }

    public function invite(Request $request)
    {
        $group = Group::where('slug', $request->group)->with('admin')->get()->first();
        if($group == null){
            return errorResponse("No group found!");
        }
        if($request->email == $request->user()->email){
            return errorResponse("You can not invite yourself!");
        }
        $user = User::where('email', $request->email)->get()->first();
        if($user == null){
            return errorResponse("User does not exist!");
        }

        if(GroupUser::where('group_id', $group->id)->where('user_id', $user->id)->first()){
            return errorResponse("This user is already a member of this group!");
        }

        if(Invitation::where('group_id', $group->id)->where('user_id', $user->id)->where('status', "Pending")->first()){
            return errorResponse("This user is already invited!");
        }

        DB::beginTransaction();
        try {
            $invitation = new Invitation();
            $invitation->group_id = $group->id;
            $invitation->user_id = $user->id;
            $invitation->created_by = $request->user()->id;
            if($invitation->save()){
                $data = [
                    'topic' => "user_" . $user->id,
                    'title' => "Membership invitation from " . $group->name,
                    'body' => "You have been invited to join the group - " . $group->name . ", by " . $request->user()->name
                ];
                MobileNotification::dispatch($data)->delay(now()->addSecond(10));
                DB::commit();
                return successResponse("Invitation sent successfully!");
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Invitation", ['e' => $e]);
            return errorResponse("Invitation could not be sent!");
        }
    }

    public function invitationAction(Request $request, $id)
    {
        $status = $request->status;
        if($status != "Accepted" && $status != "Declined" && $status != "Canceled") {
            return errorResponse("Wrong invitation action");
        }
        $invitation = Invitation::where('id', $id)->where('status', "Pending")->first();
        if($invitation == null) {
            return errorResponse("Invitation not found");
        }
        if($status == "Declined" || $status == "Accepted") {
            if($invitation->user_id != $request->user()->id) {
                return errorResponse("You do not have permission to accept/decline this invitation!");
            }
            if($status == "Accepted") {
                $group = Group::find($invitation->group_id);
                if($group == null) {
                    return errorResponse("Correxponding group does not exists!");
                }
            }
        }
        else if($status == "Canceled" && $invitation->created_by != $request->user()->id) {
            return errorResponse("You do not have permission to cancel this invitation");
        }

        DB::beginTransaction();
        try {
            $invitation->status = $status;
            $invitation->save();

            if($status == "Accepted") {
                if(GroupUser::where('group_id', $invitation->group_id)->where('user_id', $invitation->user_id)->exists()) {
                    return errorResponse("You are already a member of this group");
                }
                $groupUser = new GroupUser();
                $groupUser->group_id = $group->id;
                $groupUser->user_id = $request->user()->id;
                $groupUser->joined_at = Carbon::now();
                $groupUser->save();
            }

            DB::commit();
            return successResponse("Invitation " . strtolower($status) . " successfully!");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Invitation $status", ['e' => $e]);
            return errorResponse("Invitation could not be " . strtolower($status) . "!");
        }
    }
}
