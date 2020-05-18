<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PostRepository;
use Helper;

class SearchController extends Controller
{
    // get view 
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
        $data = PostRepository::fetchSearch(request('q'), Helper::locale(), $paginate=6, 1);
        return view('frontend.themes.'.Helper::theme().'.search.list')
        			->withrow($row ?? NULL)
        			->withdata($data['data'] ?? NULL);
    }

    // render
    public function render()
    {
        $data = PostRepository::fetchSearch(request('q'), Helper::locale(), $paginate=6, request('page'));
        
        $returnView = view('frontend.themes.'.Helper::theme().'.common.post-grid-md')
                                ->withdata($data['data'])
                                ->withpage(request('page') ?? 1)
                                ->render();
        $json = ['html'=>$returnView,'pagination'=>$data['pagination']];
        return response()->json($json);
    }
}
