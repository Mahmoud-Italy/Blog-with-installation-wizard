<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Page as PageResource;
use App\Http\Controllers\Controller;
use App\Models\Pages\Entities\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class PageController extends Controller
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

            $total = Page::fetchAll($request->headers->all());
            $rows  = PageResource::collection(Page::fetchData($request->headers->all(), $request->all()));
            $data  = ['all'        => count($total), 
                     'active'      => count($total->where('status', true)->where('trash', false)),
                     'inactive'    => count($total->where('status', false)->where('trash', false)), 
                     'trash'       => count($total->where('trash', true)),
                     'hasCreate'   => Helper::isAuthorized($request->header('accesstoken'), 'admin.pages.create'),
                     'hasEdit'     => Helper::isAuthorized($request->header('accesstoken'), 'admin.pages.edit'),
                     'hasDestroy'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.pages.destroy'),
                     'rows'        => $rows, 
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

        $row = Page::createOrUpdate(
                        NULL, 
                        $request->headers->all(),
                        $request->file('image'),
                        $request->all());
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, '.$row);
        }
    }

    public function show($id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $rows = new PageResource(Page::find(decrypt($id)));
        return self::respondSuccess(['rows'=>$rows]);
    }

    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $row = Page::createOrUpdate(
                        $id,
                        $request->headers->all(), 
                        $request->file('image'),
                        $request->all());
        if($row === true) {
            return self::respondSuccess();
        } else {
            return self::respondSomethingWrong('Unable To Update, '.$row);
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
                    $page_ids[] = $id;
                }
                Page::whereIn('id', $page_ids)->delete();
            } else {
                $page_ids[] = $ids;
                Page::where('id', $page_ids)->delete();
            }

                // save Logs
                foreach ($page_ids as $page_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Page', 
                        'log'        => 'Deleted permanently page: '.Page::getName($page_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }


    public function activeOrInactive( Request $request, $ids)
    {
        try {
            if(!$this->authorized) {
                return self::respondAccessDenied();
            }

            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $page_ids[] = $id;
                }
                foreach ($page_ids as $single) {
                    $row = Page::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $page_ids[] = $ids;
                $row = Page::where('id', $page_ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }

                // save Logs
                foreach ($page_ids as $page_id) {
                    $activeOrInactive = (Category::getStatus($cat_id)) ? 'Activate' : 'Deactivate';
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Update Page', 
                        'log'        => $activeOrInactive . '  page: '.Page::getName($page_id),
                        'created_at' => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess(['page_status'=>$status]);
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
                    $page_ids[] = $id;
                }
                Page::whereIn('id', $page_ids)->update(['trash'=>true]);
            } else {
                $page_ids[] = $ids;
                Page::where('id', $ids)->update(['trash'=>true]);
            }

                // save Logs
                foreach ($page_ids as $page_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Page', 
                        'log'        => 'Move to trash page: '.Page::getName($page_id),
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
                    $page_ids[] = $id;
                }
                page::whereIn('id', $page_ids)->update(['trash'=>false]);
            } else {
                $page_ids[] = $ids;
                page::where('id', $page_ids)->update(['trash'=>false]);
            }

                // save Logs
                foreach ($page_ids as $page_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Page', 
                        'log'        => 'Restore from trash page: '.Page::getName($page_id),
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
        $data = Page::where(['status'=>true, 'trash'=>false]);

            if($request->ids) {
                $ids = $request->ids;
                if(strpos($ids, ',') !== false) {
                    foreach(explode(',',$ids) as $id) {
                        $page_ids[] = $id;
                    }
                    $data->whereIn('id', $page_ids);
                } else {
                    $page_ids[] = $ids;
                    $data->where('id', $page_ids);
                }
            }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Page', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(PageResource::collection($data));
    }

}
