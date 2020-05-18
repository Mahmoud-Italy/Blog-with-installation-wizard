<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Message as MessageResource;
use App\Http\Controllers\Controller;
use App\Models\Messages\Entities\Message;
use Illuminate\Http\Request;
use Carbon/Carbon;
use Helper;
use Log;

class MessageController extends Controller
{
    protected $authorized = true;
    public function __construct(Request $request)
    {
        if(!Helper::isAuthorized($request->header('accesstoken'), $request->header('permission'))) {
            $this->authorized = false;
        }
    }

    public function index(Request $request)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            $total = Message::all();
            $rows  = MessageResource::collection(Message::fetchData($request->all()));
            $data  = ['all'      => count($total), 
                    'seen'       => count($total->where('status', true)->where('trash', false)),
                    'unseen'     => count($total->where('status',false)->where('trash', false)), 
                    'trash'      => count($total->where('trash', true)),
                    'hasShow'    => Helper::isAuthorized($request->header('accesstoken'), 'admin.messages.show'),
                    'hasDestroy' => Helper::isAuthorized($request->header('accesstoken'), 'admin.messages.destroy'),
                    'rows'       => $rows, 
                    'pagination' => self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
        
    }

    public function show($id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = Message::find(decrypt($id));
        if(!$row->status) {
        	$row->status = true;
        	$row->save();
        }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Show Message', 
                    'log'        => 'Show message: '.Message::getEmail($id),
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $rows = new MessageResource($row);
        return self::respondSuccess(['rows'=>$rows]);
    }

    public function destroy(Request $request, $ids)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $msg_ids[] = $id;
                }
                Message::whereIn('id', $msg_ids)->delete();
            } else {
                $msg_ids[] = $ids;
                Message::where('id', $msg_ids)->delete();
            }

                // save Logs
                foreach ($msg_ids as $msg_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Message', 
                        'log'        => 'Deleted permanently message: '.Message::getEmail($msg_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    
    public function moveToTrash(Request $request, $ids)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $msg_ids[] = $id;
                }
                Message::whereIn('id', $msg_ids)->update(['trash'=>true]);
            } else {
                $msg_ids[] = $ids;
                Message::where('id', $msg_ids)->update(['trash'=>true]);
            }

                // save Logs
                foreach ($msg_ids as $msg_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Message', 
                        'log'        => 'Move to trash message: '.Message::getEmail($msg_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function restoreFromTrash(Request $request, $ids)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $msg_ids[] = $id;
                }
                Message::whereIn('id', $msg_ids)->update(['trash'=>false]);
            } else {
                $msg_ids[] = $ids;
                Message::where('id', $msg_ids)->update(['trash'=>false]);
            }

                // save Logs
                foreach ($msg_ids as $msg_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Message', 
                        'log'        => 'Restore from trash message: '.Message::getEmail($msg_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $data = Message::where(['status'=>true, 'trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $msg_ids[] = $id;
                    }
                    $data->whereIn('id', $msg_ids);
                } else {
                    $msg_ids[] = $ids;
                    $data->where('id', $msg_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Message', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(MessageResource::collection($data));
    }

}
