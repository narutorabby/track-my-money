<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\MobileNotification;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Record;
use App\Models\RecordShare;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $slug = $request->slug;
        $type = $request->type;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $pagination = $request->pagination ?? 10;

        $records = Record::where('type', $type)->orderBy("id", "DESC")->with('user', 'group', 'creator', 'editor');
        if($slug){
            $group = Group::where('slug', $request->slug)->first();
            if($group == null) {
                return errorResponse("Group does not exists!");
            }
            $records = $records->where('group_id', $group->id);
        }
        if($date_from){
            $records = $records->whereDate('date', '>=', Carbon::parse($date_from));
        }
        if($date_to){
            $records = $records->whereDate('date', '>=', Carbon::parse($date_to));
        }

        $records = $records->paginate($pagination);
        return successResponse("Personal records", $records);
    }

    public function show($id)
    {
        $record = Record::where('id', $id)->with(['user', 'group'])->first();
        return successResponse("Record details", $record);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required', 'numeric',
            'date' => 'required', 'string',
            'title' => 'required', 'string', 'max:20',
            'description' => 'nullable', 'string', 'max:500'
        ]);
        if ($validator->fails()) {
            return warningResponse($validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $record = new Record();
            $record->type = $request->type;
            $record->amount = $request->amount;
            $record->date = Carbon::parse($request->date);
            $record->title = $request->title;
            $record->description = $request->description;
            if($request->group_id) {
                $record->group_id = $request->group_id;
            }
            else {
                $record->user_id = $request->user()->id;
            }
            $record->created_by = $request->user()->id;
            $record->save();

            if($record->type == "Contribution" || $record->type == "Bill") {
                if($record->type == "Contribution"){
                    $recordhare = new RecordShare();
                    $recordhare->user_id = $request->member;
                    $recordhare->record_id = $record->id;
                    $recordhare->share = $record->amount;
                    $recordhare->save();
                }
                else if($record->type == "Bill"){
                    foreach ($request->members as $member) {
                        $recordhare = new RecordShare();
                        $recordhare->user_id = $member;
                        $recordhare->record_id = $record->id;
                        $recordhare->share = ($record->amount / count($request->members));
                        $recordhare->save();
                    }
                }
                $group = Group::find($record->group_id);
                $title = "Group: " . $group->name;
                $topic = $group->slug;
                $body = "New " . strtolower($record->type) . " of amount " . $record->amount . " is created by " . $request->user()->name;

                $data = [
                    'topic' => $topic,
                    'title' => $title,
                    'body' => $body
                ];
                MobileNotification::dispatch($data)->delay(now()->addSecond(10));
            }

            DB::commit();
            return successResponse("Record created successfully!", $record);
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not create record!");
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $record = Record::where('id', $id)->with('group')->first();
            if($record == null) {
                return errorResponse("Record does not exists!");
            }
            if(($record->user_id && $record->user_id != $request->user()->id) || ($record->group_id && $record->group->created_by != $request->user()->id)) {
                return errorResponse("You do not have permission to update this record");
            }
            $record->amount = $request->amount;
            $record->date = Carbon::parse($request->date);
            $record->title = $request->title;
            $record->description = $request->description;
            $record->updated_by = $request->user()->id;
            $record->save();
            DB::commit();
            return successResponse("Record updated successfully!", $record);
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not update record!");
        }
    }

    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $record = Record::where('id', $id)->with('group')->first();
            if($record == null) {
                return errorResponse("Record does not exists!");
            }
            if(($record->user_id && $record->user_id != $request->user()->id) || ($record->group_id && $record->group->created_by != $request->user()->id)) {
                return errorResponse("You do not have permission to delete this record");
            }

            $record->delete();
            DB::commit();
            return successResponse("Record deleted successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse("Could not delete record!");
        }
    }
}
