<?php

namespace App\Http\Controllers;

use App\Models\Categories\Entities\Category;
use App\Models\Metas\Entities\MetaTranslation;
use Illuminate\Http\Request;
use CategoryRepository;
use PostRepository;
use Helper;

class CategoryController extends Controller
{	
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
        // Get id by Slug
        $cat_id = Category::getId(request('category'));

        // is Active
        $isActive = Category::where('id', $cat_id)->where(['status'=>true, 'trash'=>false])->first();
        if(!$isActive) {
            abort(404);
        }

        // Find Category
        $row = CategoryRepository::findCategory($cat_id);

        // Just figure out it's Main category or subCategory
        if($row->parent_id == 0) {
            $rootName   = NULL;
            $rootSlug   = NULL;
            $subName    = $row->name;

            //
            $subcat_ids = Category::where('parent_id', $row->id)->pluck('id');
            $data       = PostRepository::fetchPostsInCategory($row->id, $subcat_ids, $paginate=6);
        } else {

            $root        = CategoryTranslation::where('category_id', $row->parent_id)->first();
            $rootName    = $root->name;
            $modelPath   = 'App\Models\Categories\Entities\Category';
            $rootSlug    = MetaTranslation::getAttr($row->parent_id, $modelPath, 'slug', 'en');
            $subName     = $row->name;
            //
            $subcat_id[] = $row->id;
            $data        = PostRepository::fetchPostsInCategory($row->id, $subcat_id, $paginate=6);
        }

        return view($this->getView('frontend.themes.'.Helper::theme().'.categories.list'))
                    ->withrow($row)
                    ->withrootName($rootName)
                    ->withrootSlug($rootSlug)
                    ->withsubName($subName)
                    ->withdata($data['data'] ?? NULL);
        
    }

    // render
    public function render()
    {
        // Get id by Slug
        $cat_id = Category::getId(request('category'));

        // Find Category
        $row = CategoryRepository::findCategory($cat_id);

        // Just figure out it's Main category or subCategory
        if(is_null($row->parent_id)) {
            $parent_ids  = Category::where('parent_id', $row->id)->pluck('id');
            $subcat_ids  = $parent_ids->merge($row->id);
            $data        = PostRepository::fetchPostsInCategory($row->id, $subcat_ids, $paginate=6, request('page'));
        } else {
            $subcat_id[] = $row->id;
            $data        = PostRepository::fetchPostsInCategory($row->id, $subcat_id, $paginate=6, request('page'));
        }

       $returnView = view('frontend.themes.'.Helper::theme().'.common.post-grid-md')
                            ->withdata($data['data'])
                            ->withpage(request('page') ?? 1) 
                            ->render();
       $json = ['html'=>$returnView, 'pagination'=>$data['pagination']];
       return response()->json($json);
    }


}
