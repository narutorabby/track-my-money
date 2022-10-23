<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RecordTrait;
use App\Models\Group;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    use RecordTrait;

    public function index(Request $request)
    {
        $params = [];
        if($request->slug) {
            $group = Group::where('slug', $request->slug)->first();
            if($group == null) {
                return errorResponse("Group does not exists!");
            }
            $params['group_id'] = $group->id;
        }
        else {
            $params['user_id'] = $request->user()->id;
        }
        $params['type'] = $request->type;
        $params['date_from'] = $request->date_from;
        $params['date_to'] = $request->date_to;
        $params['pagination'] = $request->pagination;

        $records = $this->list($params);
        return successResponse("Personal records", $records);
    }

    public function show($id)
    {
        $record = $this->details($id);
        return successResponse("Record details", $record);
    }

    public function store(Request $request)
    {
        $recordData = [];
        $recordData['type'] = $request->type;
        $recordData['amount'] = $request->amount;
        $recordData['date'] = Carbon::parse($request->date)->format('Y-m-d');
        $recordData['title'] = $request->title;
        $recordData['description'] = $request->description;
        if($request->group_id) {
            $recordData['group_id'] = $request->group_id;
        }
        else {
            $recordData['user_id'] = $request->user()->id;
        }
        $recordData['created_by'] = $request->user()->id;

        $res = $this->create($recordData);
        if($res){
            return successResponse("Record created successfully!", $res);
        }
        return errorResponse("Could not create record!");
    }

    public function update(Request $request, $id)
    {
        $record = Record::where('id', $id)->first();
        if($record == null) {
            return errorResponse("Record does not exists!");
        }
        $recordData = [];
        $recordData['amount'] = $request->amount;
        $recordData['date'] = Carbon::parse($request->date)->format('Y-m-d');
        $recordData['title'] = $request->title;
        $recordData['description'] = $request->description;
        $recordData['updated_by'] = $request->user()->id;

        $res = $this->edit($recordData, $record);
        if($res){
            return successResponse("Record updated successfully!", $res);
        }
        return errorResponse("Could not update record!");
    }

    public function delete(Request $request, $id)
    {
        $record = Record::where('id', $id)->with('group')->first();
        if($record == null) {
            return errorResponse("Record does not exists!");
        }
        if(($record->user_id && $record->user_id != $request->user()->id) || ($record->group_id && $record->group->created_by != $request->user()->id)) {
            return errorResponse("You do not have permission to delete this record");
        }

        $res = $this->remove($id);
        if($res){
            return successResponse("Record deleted successfully!");
        }
        return errorResponse("Could not delete record!");
    }
}
