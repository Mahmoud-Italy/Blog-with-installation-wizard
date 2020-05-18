<?php

namespace App\Http\Controllers;

use App\Http\Resources\Tag as TagResource;
use App\Http\Resources\Page as PageResource;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Tags\Entities\Tag;
use App\Models\Pages\Entities\Page;
use App\Models\Posts\Entities\Post;
use App\Models\Categories\Entities\Category;
use Illuminate\Http\Request;
use Helper;
use DB;

class SitemapController extends Controller
{
    public function index()
    {
    	$pages = $this->toCollection(PageResource::collection(Page::where(['status'=>true, 'trash'=>false])->get()));
        $tags = $this->toCollection(TagResource::collection(Tag::where(['status'=>true, 'trash'=>false])->get()));
        $posts = $this->toCollection(PostResource::collection(Post::where(['status'=>true, 'trash'=>false])->get()));
        $categories = $this->toCollection(CategoryResource::collection(Category::where(['status'=>true, 'trash'=>false])->get()));

	    return response()->view('sitemap.index', [
		        'pages' => $pages,
		        'categories' => $categories,
		        'posts' => $posts,
                'tags' => $tags,
		    ])->header('Content-Type', 'text/xml');
    }

    // Convert json to Collection Laravel
    public function toCollection($rows='')
    {
        $json = response()->json(['rows'=>$rows]);
        $decode = json_decode(json_encode($json), true);
        return json_decode(json_encode($decode['original']['rows']));
    }
}
