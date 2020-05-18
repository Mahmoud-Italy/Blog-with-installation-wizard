<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Media as MediaResource;
use App\Models\Media\Entities\Media;
use Illuminate\Http\Request;
use Helper;

class MediaController extends Controller
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
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }

        $fileSize = Media::getFileSize();
        $rows     = MediaResource::collection(Media::orderBy('id','DESC')->paginate(10));
        $data     = ['rows'=>$rows, 'fileSize'=>$fileSize, 'pagination'=>self::getPagination($rows)];
        return self::respondSuccess($data);        
    }

    public function store(Request $request)
    {
        if(!$this->authorized) {
            return self::respondAccessDenied();
        }
        
        $row = Media::createOrUpdate(
                        $request->headers->all(), 
                        $request->file('file'));
    	if($row === true) {
    		return self::respondCreated();
    	} else {
    		return self::respondSomethingWrong('Internet server error, '.$row);
    	}
    }
}
