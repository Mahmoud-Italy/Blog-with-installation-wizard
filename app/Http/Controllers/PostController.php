<?php

namespace App\Http\Controllers;

use App\Models\Posts\Entities\Post_view;
use App\Models\Posts\Entities\Post;
use Illuminate\Http\Request;
use PostRepository;
use Helper;

class PostController extends Controller
{
	public function __contsruct()
    {
        //
    }

	// get View & Support AMP
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

    // Show Post
    public function show($post='')
    {
        // Explode id from slug
        $post_id = Post::explodeIdFromSlug($post);

        // Is Active
        $isActive = Post::where('id', $post_id)->where(['status'=>true, 'trash'=>false])->first();
        if(!$isActive) {
            abort(404);
        }

        // Find Post detail
        $row = PostRepository::findPost($post_id);

        // Save View
        Post_view::addView($post_id);

        // get Next & Prev Post
        $getNextPost = PostRepository::getNextPost($row->cat_id, $post_id) ?? NULL;
        $getPrevPost = PostRepository::getPrevPost($row->cat_id, $post_id) ?? NULL;

        // Get Related
        $getRelated = PostRepository::getRelatedPost($row->cat_id, $post_id, $paginate=3) ?? NULL;

        // Return data
        return view($this->getView('frontend.themes.'.Helper::theme().'.posts.show'))
        			->withrow($row)
                    ->withnext($getNextPost)
                    ->withprev($getPrevPost)
                    ->withrelated($getRelated);
    }

}
