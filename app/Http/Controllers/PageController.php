<?php

namespace App\Http\Controllers;

use App\Http\Resources\Page as PageResource;
use App\Models\Pages\Entities\PageTranslation;
use App\Models\Pages\Entities\Page;
use App\Models\Messages\Entities\Message;
use App\Models\Visitors\Entities\Visitor;
use Illuminate\Http\Request;
use PageRepository;
use PostRepository;
use Helper;

class PageController extends Controller
{
	// Defined View path
	//protected $view = 'frontend.themes.'.Helper::theme().'.home';
    public function __contsruct()
    {
        //
    }

    // Tell laravel to retrieve the model by the $slug instead of the default $id
    public function getRouteKeyName()
    {
        return 'slug';
    }

	// Get view & Support AMP
    private function getView($viewName)
    {
        if (request()->segment(1) == 'amp') {
            $getFile = str_replace('frontend','amp', $viewName);
            if (view()->exists($getFile . '-amp')) {
                $viewName = $getFile . '-amp';
            } else {

            }
        }
        return $viewName;
    }


    // Home Page
    public function home()
    {
        //Save As Visitor
        Visitor::saveAsVisitor();

        $row = PageRepository::findPage(1);
        return view($this->getView('frontend.themes.'.Helper::theme().'.home.list'))->withrow($row);
    }
    public function render()
    {
        $data = PostRepository::fetchLatestPosts('latest', $paginate=9, request('page'));

        // Exception for mitte theme
        if(Helper::theme() == 'mitte') {
            $fileName = 'post-list';
        } else {
            $fileName = 'post-grid-md';
        }

            $returnView = view('frontend.themes.'.Helper::theme().'.common.'.$fileName)
                            ->withdata($data['data'])
                            ->withpage(request('page') ?? 1)
                            ->render();
        
        $json = ['html' => $returnView, 'pagination' => $data['pagination']];
        return response()->json($json);
    }
    public function trends()
    {
        $data = PostRepository::fetchTrending();
        $returnView = view('frontend.themes.'.Helper::theme().'.trends.trend')
                            ->withdata($data)
                            ->render();
        $json = ['html' => $returnView];
        return response()->json($json);
    }



    // Dynamic Pages
    public function show($page)
    {
		// get id by page
        $pgid = PageTranslation::getId($page);

        //is Active
        $isActive = Page::where('id',$pgid)
                        ->where(['status'=>true, 'trash'=>false])
                        ->first();
        if(!$isActive) {
            return Abort(404);
        }

        $row = new PageResource(PageRepository::findPage($pgid));
        return view($this->getView('frontend.themes.'.Helper::theme().'.pages.show'))->withrow($row);
    }


    // Contacts
    public function contacts($page='contacts')
    {
        // get id by page
        $pgid = PageTranslation::getId($page);

        // is Active
        $isActive = Page::where('id',$pgid)
                        ->where(['status'=>true, 'trash'=>false])
                        ->first();
        if(!$isActive) {
            return Abort(404);
        }

        $row = new PageResource(PageRepository::findPage($pgid));
        return view('frontend.themes.'.Helper::theme().'.pages.contacts')->withrow($row);
    }

    public function doContacts(Request $request)
    {
        try {
            Message::create($request->all());
            return self::respondCreated();
        } catch (\Exception $e) {
            return self::respondSomethingWrong($e->getMessage());
        }
    }


}
