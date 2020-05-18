<?php

namespace App\Http\Controllers\backend;

use App\Http\Resources\Post as PostResource;
use App\Http\Controllers\Controller;
use App\Models\Posts\Entities\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper;
use Log;

class PostController extends Controller
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

            $total = Post::fetchAll($request->headers->all());
            $rows  = PostResource::collection(Post::fetchData($request->headers->all(), $request->all()));
            $data  = ['all'      => count($total), 
                    'published'  => count($total->where('status', true)->where('trash', false)),
                    'draft'      => count($total->where('status', false)->where('trash', false)), 
                    'trash'      => count($total->where('trash', true)),
                    'hasCreate'  => Helper::isAuthorized($request->header('accesstoken'), 'admin.posts.create'),
                    'hasEdit'    => Helper::isAuthorized($request->header('accesstoken'), 'admin.posts.edit'),
                    'hasDestroy' => Helper::isAuthorized($request->header('accesstoken'), 'admin.posts.destroy'),
                    'rows'       => $rows,
                    'pagination' => self::getPagination($rows)];
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

        $row = Post::createOrUpdate(
                        NULL, 
                        $request->headers->all(),
                        $request->file('image'),
                        $request->tags,
                        $request->all());
        if($row === true) {
            return self::respondCreated();
        } else {
            return self::respondSomethingWrong('Internet Server Error, '.$row);
        }
    }

    
    public function show(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $rows = new PostResource(Post::find(decrypt($id)));
        return self::respondSuccess(['rows'=>$rows]);
    }

    
    public function update(Request $request, $id)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        } 
        
        $row = Post::createOrUpdate(
                        decrypt($id), 
                        $request->headers->all(),
                        $request->file('image'),
                        $request->tags,
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
                    $post_ids[] = $id;
                }
                Post::whereIn('id', $post_ids)->delete();
            } else {
                $post_ids[] = $ids;
                Post::where('id', $post_ids)->delete();
            }

                // save Logs
                foreach ($post_ids as $post_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Post', 
                        'log'        => 'Deleted permanently post: '.Post::getName($post_id),
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
                    $post_ids[] = $id;
                }
                foreach ($post_ids as $single) {
                    $row = Post::where('id', $single)->first();
                    $row->status = ($row->status) ? false : true;
                    $row->trash = false;
                    $row->save();
                    $status = $row->status;
                }
            } else {
                $post_ids[] = $ids;
                $row = Post::where('id', $post_ids)->first();
                $row->status = ($row->status) ? false : true;
                $row->trash = false;
                $row->save();
                $status = $row->status;
            }   

                // save Logs
                foreach ($post_ids as $post_id) {
                    $activeOrInactive = (Post::getStatus($post_id)) ? 'Publish' : 'Draft';
                    $log[] = [
                        'user_id'     => Helper::whoIs($request->header('accesstoken')), 
                        'action'      => 'Update Post', 
                        'log'         => $activeOrInactive. ' post: '.Post::getName($post_id),
                        'created_at'  => Carbon::now()
                    ];
                } Log::insert($log);

            return self::respondSuccess(['post_status'=>$status]);
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
                    $post_ids[] = $id;
                }
                Post::whereIn('id', $post_ids)->update(['trash'=>true]);
            } else {
                $post_ids[] = $ids;
                Post::where('id', $post_ids)->update(['trash'=>true]);
            }   

                // save Logs
                foreach ($post_ids as $post_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Destroy Post', 
                        'log'        => 'Move to trash post: '.Post::getName($post_id),
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
                    $post_ids[] = $id;
                }
                Post::whereIn('id', $post_ids)->update(['trash'=>false]);
            } else {
                $post_ids[] = $ids;
                Post::where('id', $post_ids)->update(['trash'=>false]);
            }   

                // save Logs
                foreach ($post_ids as $post_id) {
                    $log[] = [
                        'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                        'action'     => 'Restore Post', 
                        'log'        => 'Restore from trash post: '.Post::getName($post_id),
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
        $data = Post::where(['status'=>true, 'trash'=>false]);

        if($request->ids) {
            $ids = $request->ids;
            if(strpos($ids, ',') !== false) {
                foreach(explode(',',$ids) as $id) {
                    $post_ids[] = $id;
                }
                $data->whereIn('id', $post_ids);
            } else {
                $post_ids[] = $ids;
                $data->where('id', $post_ids);
            }
        }

                // save Logs
                $log[] = [
                    'user_id'    => Helper::whoIs($request->header('accesstoken')), 
                    'action'     => 'Export Post', 
                    'log'        => 'Export data successfully',
                    'created_at' => Carbon::now()
                ];
                Log::insert($log);

        $data = $data->orderBy('id','DESC')->get();
        return self::respondSuccess(PostResource::collection($data));
    }



}
