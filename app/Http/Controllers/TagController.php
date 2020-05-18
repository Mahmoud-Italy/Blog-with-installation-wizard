<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PostRepository;
use Helper;

class TagController extends Controller
{
    // Constructor
	public function __contsruct()
    {
        //
    }

	// get view & Support AMP
    private function getView($viewName)
    {
        if (request()->segment(1) == 'amp') {
            $getFile = str_replace('frontend','amp', $viewName);
            if (view()->exists($getFile . '-amp')) {
                $viewName = $getFile . '-amp';
            } else {
                abort(404);
            }
        }
        return $viewName;
    }
    

    // index
    public function index()
    {
        $data = PostRepository::fetchTag(request('tag'), Helper::locale(), $paginate=6);
        return view($this->getView('frontend.themes.'.Helper::theme().'.tags.list'))
        		->withrow($row ?? NULL)
        		->withdata($data['data'] ?? NULL);
    }

    // render
    public function render()
    {
        $data = PostRepository::fetchTag(request('tag'), Helper::locale(), $paginate=6, request('page'));
        $returnView = view('frontend.themes.'.Helper::theme().'.common.post-grid-md')
                                    ->withdata($data['data'])
                                    ->withpage(request('page') ?? 1)
                                    ->render();
        $json = ['html'=>$returnView, 'pagination'=>$data['pagination']];
        return response()->json($json);
    }
}
