<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Tag as TagResource;
use App\Http\Controllers\Controller;
use App\Models\Tags\Entities\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class TagController extends Controller
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

            $total = Tag::all();
            $tags  = Tag::getTagsNameforMultiSelect();
            $rows  = TagResource::collection(Tag::fetchData($request->headers->all(), $request->all()));
            $data  = ['all'       => count($total), 
                    'active'      => count($total->where('status', true)->where('trash', false)),
                    'inactive'    => count($total->where('status', false)->where('trash', false)), 
                    'trash'       => count($total->where('trash', true)),
                    'hasCreate'   => Helper::isAuthorized($request->header('accesstoken'), 'admin.tags.create'),
                    'hasEdit'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.tags.edit'),
                    'hasDestroy'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.tags.destroy'),
                    'rows'        => $rows, 
                    'tags'        => $tags,
                    'pagination'  => self::getPagination($rows)];
            return self::respondSuccess($data);
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }
        $row = Tag::createOrUpdate(
                        NULL, 
                        $request->headers->all(), 
                        $request->all());
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, ',$row);
        }
    }

    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = Tag::createOrUpdate(
                        $id, 
                        $request->headers->all(), 
                        $request->all());
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update Entry, '.$row);
        }
    }

    public function destroy(Request $request, $ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $tag_ids[] = $id;
                }
                Tag::whereIn('id', $tag_ids)->delete();
            } else {
                $tag_ids[] = $ids;
                Tag::where('id', $tag_ids)->delete();
            }

                // save Logs
                foreach ($tag_ids as $tag_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Tag', 
                        'log'        => 'Deleted permanently tag: '.Tag::getName($tag_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess();
    	} catch (\Exception $e) {
    		return self::respondSomethingWrong($e->getMessage());
    	}
    }


    public function activeOrInactive(Request $request, $ids)
    {
    	try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $tag_ids[] = $id;
                }
                foreach ($tag_ids as $single) {
                    $row = Tag::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $tag_ids[] = $ids;
                $row = Tag::where('id', $tag_ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }   

                // save Logs
                foreach ($tag_ids as $tag_id) {
                    $activeOrInactive = (Tag::getStatus($tag_id)) ? 'Activate' : 'Deactivate';
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Update Tag', 
                        'log'        => $activeOrInactive. ' tag: '.Tag::getName($tag_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

    		return self::respondSuccess(['tag_status'=>$status]);
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
                    $tag_ids[] = $id;
                }
                Tag::whereIn('id', $tag_ids)->update(['trash'=>true]);
            } else {
                $tag_ids[] = $ids;
                Tag::where('id', $tag_ids)->update(['trash'=>true]);
            }

                // save Logs
                foreach ($tag_ids as $tag_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Update Tag', 
                        'log'        => 'Move to trash tag: '.Tag::getName($tag_id),
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
                    $tag_ids[] = $id;
                }
                Tag::whereIn('id', $tag_ids)->update(['trash'=>false]);
            } else {
                $tag_ids[] = $ids;
                Tag::where('id', $tag_ids)->update(['trash'=>false]);
            }

                // save Logs
                foreach ($tag_ids as $tag_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Tag', 
                        'log'        => 'Restore from trash tag: '.Tag::getName($tag_id),
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
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $data = Tag::where(['status'=>true, 'trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $tag_ids[] = $id;
                    }
                    $data->whereIn('id', $tag_ids);
                } else {
                    $tag_ids[] = $ids;
                    $data->where('id', $tag_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Tag', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(TagResource::collection($data));
    }
}
