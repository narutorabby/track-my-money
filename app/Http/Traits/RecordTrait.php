<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

trait RecordTrait {
    public function list(array $params)
    {
        $where = [];
        $pagination = 10;

        $where[] = ['type', '=', $params['type']];
        if($params['type'] == "Contribution" || $params['type'] == "Bill") {
            $where[] = ['group_id', '=', $params['group_id']];
        }
        else if($params['type'] == "Income" || $params['type'] == "Expense") {
            $where[] = ['user_id', '=', $params['user_id']];
        }

        if($params['date_from'] != null){
            $where[] = ['date', '>=', Carbon::parse($params['date_from'])->format('Y-m-d')];
        }
        if($params['date_to'] != null){
            $where[] = ['date', '<=', Carbon::parse($params['date_to'])->format('Y-m-d')];
        }

        $pagination = $params['pagination'] ?? 10;

        $records = Record::where($where);

        $records = $records->orderBy("id", "DESC")->with('user', 'group', 'creator', 'editor')->paginate($pagination);
        return $records;
    }

    public function details($id)
    {
        return Record::where('id', $id)->with(['user', 'group'])->first();
    }

    public function create(array $recordData)
    {
        DB::beginTransaction();
        try {
            $record = Record::create($recordData);

            // if($record->type == "Contribution" || $record->type == "Bill"){
            //     $model = Group::find($record->group_id);
            //     $title = "Group: " . $model->name;
            //     $topic = $model->slug;
            //     $body = "New " . strtolower($record->type) . " of amount " . $record->amount . " is created by " . auth()->guard('api')->user()->name;

            //     $data = [
            //         'topic' => $topic,
            //         'title' => $title,
            //         'body' => $body
            //     ];
            //     SendMobileNotification::dispatch($data)->delay(now()->addSecond(10));
            // }

            DB::commit();

            return $record;
        } catch (Exception $e) {
            DB::rollback();
            return null;
        }
    }

    public function edit(array $recordData, Record $record)
    {
        DB::beginTransaction();
        try {
            $record = $record->update($recordData);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function remove(Record $record)
    {
        DB::beginTransaction();
        try {
            $record->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
