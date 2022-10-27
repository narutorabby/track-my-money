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

class InvitationController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->status ?? null;
        $group_id = $request->group_id ?? null;
        $invitations = Invitation::with('group', 'user');
        if($group_id) {
            $invitations = $invitations->where('group_id', $group_id);
        }
        else {
            $invitations = $invitations->where('user_id', $request->user()->id);
        }
        if($status != null && $status != "Any") {
            $invitations = $invitations->where('status', $status);
        }
        $invitations = $invitations->get();
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
            return errorResponse("Invitation could not be sent!");
        }
    }

    public function acceptInvitation(Request $request, $id)
    {
        $invitation = Invitation::where('id', $id)->where('status', "Pending")->first();
        if($invitation == null) {
            return errorResponse("Invitation not found");
        }
        if($invitation->user_id != $request->user()->id) {
            return errorResponse("You do not have permission to accept this invitation");
        }
        $group = Group::find($invitation->group_id);
        if($group == null) {
            return errorResponse("Correxponding group does not exists!");
        }

        DB::beginTransaction();
        try {
            $invitation->status = "Accepted";
            $invitation->save();

            $groupUser = new GroupUser();
            $groupUser->group_id = $group->id;
            $groupUser->user_id = $request->user()->id;
            $groupUser->joined_at = Carbon::now();
            $groupUser->save();

            DB::commit();
            return successResponse("Invitation accepted successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Invitation could not be accepted!");
        }
    }

    public function declineInvitation(Request $request, $id)
    {
        $invitation = Invitation::where('id', $id)->where('status', "Pending")->first();
        if($invitation == null) {
            return errorResponse("Invitation not found");
        }
        if($invitation->user_id != $request->user()->id) {
            return errorResponse("You do not have permission to decline this invitation");
        }
        return $this->takeAction($invitation, "Declined");
    }

    public function cancelInvitation(Request $request, $id)
    {
        $invitation = Invitation::where('id', $id)->where('status', "Pending")->first();
        if($invitation == null) {
            return errorResponse("Invitation not found");
        }
        if($invitation->created_by != $request->user()->id) {
            return errorResponse("You do not have permission to cancel this invitation");
        }
        return $this->takeAction($invitation, "Canceled");
    }

    private function takeAction(Invitation $invitation, $status)
    {
        try {
            $invitation->status = $status;
            $invitation->save();
            return successResponse("Invitation " . strtolower($status) . " successfully!");

        } catch (Exception $e) {
            return errorResponse("Invitation could not be " . strtolower($status) . "!");
        }
    }
}
