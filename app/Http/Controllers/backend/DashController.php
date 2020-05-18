<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;
use App\Http\Controllers\Controller;
use App\Models\Users\Entities\User;
use App\Models\Posts\Entities\Post;
use App\Models\Posts\Entities\Post_view;
use App\Models\Visitors\Entities\Visitor;
use PostRepository;
use CategoryRepository;
use Carbon\Carbon;
use Helper;

class DashController extends Controller
{
	// Dashboard
   public function index()
   {
   	return view('backend.root');
   }

   
   // Total Posts
   public function totalPosts(Request $request)
   {
      $data = Post::fetchPeriod($request->headers->all(), $request->days);
      return self::respondSuccess(['total'=>$data['total'], 'percentage'=>$data['percentage'], 'arrow'=>$data['arrow']]);
   }

   // Total Views
   public function totalViews(Request $request)
   {
      $data = Post_view::fetchPeriod($request->headers->all(), $request->days);
      return self::respondSuccess(['total' => $data['total'], 'percentage'=>$data['percentage'], 'arrow'=>$data['arrow']]);
   }

   // Total Users
   public function totalUsers(Request $request)
   {
      $data = User::fetchPeriod($request->headers->all(), $request->days);
      return self::respondSuccess(['total'=>$data['total'], 'percentage'=>$data['percentage'], 'arrow'=>$data['arrow']]);
   }

   // Total Visitors
   public function totalVisitors(Request $request)
   {
      $data = Visitor::fetchPeriod($request->headers->all(), $request->days);
      return self::respondSuccess(['total'=>$data['total'], 'percentage'=>$data['percentage'], 'arrow'=>$data['arrow']]);
   }

   // lineChart
   public function lineChart(Request $request)
   {
      $data = Helper::postViewslineChart($request->headers->all(), $request->type);
      return self::respondSuccess(['rows'=>$data]);
   }

   // pieChart
   public function pieChart(Request $request)
   {
      $data = Visitor::fetchCountries($request->headers->all(), $request->days);
      return self::respondSuccess(['rows'=>$data]);
   }

   // Trending Five Posts
   public function trendingPosts(Request $request)
   {
      $data = PostRepository::fetchTrending('trending', 5);
      return self::respondSuccess(['rows'=>$data]);
   }

   // Top Five Categories
   public function topCategories(Request $request)
   {
      $xaxis = $series = [];
      $categories = CategoryRepository::fetchCategories('cateogies', 5);
      foreach($categories as $category) {
         $xaxis[] = $category->name;
         $series[] = $category->posts;
      }
      return self::respondSuccess(['xaxis'=>$xaxis,'series'=>$series]);
   }










   // Google Analaytics fetchs
   public function fetchMostVisitedPages($days=7)
   {
      $googleAnalytics = Analytics::fetchMostVisitedPages(Period::days($days));
      return response()->json($googleAnalytics);
   }

   public function fetchVisitorsAndPageViews($days=7)
   {
   	$googleAnalytics = Analytics::fetchVisitorsAndPageViews(Period::days($days));
   	return response()->json($googleAnalytics);
   }

   public function fetchTotalVisitorsAndPageViews($days=7)
   {
   	$googleAnalytics = Analytics::fetchTotalVisitorsAndPageViews(Period::days($days));
   	$monthly = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
   	return response()->json(['rows'=>$monthly]);
   }

   public function fetchTopReferrers($days=7)
   {
   	$googleAnalytics = Analytics::fetchTopReferrers(Period::days($days));
   	return response()->json($googleAnalytics);
   }

	public function fetchUserTypes($days=7)
	{
   	$googleAnalytics = Analytics::fetchUserTypes(Period::days($days));
   	return response()->json($googleAnalytics);
	}

   public function fetchTopBrowsers($days=7)
   {
   	$googleAnalytics = Analytics::fetchTopBrowsers(Period::days($days));
   	return response()->json($googleAnalytics);
   }
   
}
