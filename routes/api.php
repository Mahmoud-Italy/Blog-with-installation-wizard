<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/** BACKEND **/
// dont' forget middleware = admin later
Route::group(['prefix'=>'dashboard', 'namespace'=>'backend'], function(){
	
	// Auth 
	Route::post('login', 'AuthController@login')->name('api.login');
	Route::post('forgot', 'AuthController@forgot');
	Route::post('reset', 'AuthController@reset');

	// Explore
	// Route::get('explore', 'DashController@explore');
	Route::get('explore/totalPosts', 'DashController@totalPosts');
	Route::get('explore/totalViews', 'DashController@totalViews');
	Route::get('explore/totalUsers', 'DashController@totalUsers');
	Route::get('explore/totalVisitors', 'DashController@totalVisitors');
	Route::get('explore/lineChart', 'DashController@lineChart');
	Route::get('explore/pieChart', 'DashController@pieChart');
	Route::get('explore/trendingPosts', 'DashController@trendingPosts');
	Route::get('explore/topCategories', 'DashController@topCategories');


	Route::get('fetchMostVisitedPages', 'DashController@fetchMostVisitedPages');
	Route::get('fetchVisitorsAndPageViews', 'DashController@fetchVisitorsAndPageViews');
	Route::get('fetchTotalVisitorsAndPageViews', 'DashController@fetchTotalVisitorsAndPageViews');
	Route::get('fetchTopReferrers', 'DashController@fetchTopReferrers');
	Route::get('fetchUserTypes', 'DashController@fetchUserTypes');
	Route::get('fetchTopBrowsers', 'DashController@fetchTopBrowsers');


	// Post
	Route::apiResource('posts', 'PostController');
	Route::put('posts/status/{id}', 'PostController@activeOrInactive');
	Route::delete('posts/trash/{id}', 'PostController@moveToTrash');
	Route::patch('posts/restore/{id}', 'PostController@restoreFromTrash');
	Route::get('posts/export/file', 'PostController@export');

	// Categories
	Route::apiResource('categories', 'CategoryController');
	Route::get('categories/sub/show/{category}', 'CategoryController@subcategory');
	Route::put('categories/status/{id}', 'CategoryController@activeOrInactive');
	Route::delete('categories/trash/{id}', 'CategoryController@moveToTrash');
	Route::patch('categories/restore/{id}', 'CategoryController@restoreFromTrash');
	Route::get('categories/export/file', 'CategoryController@export');

	// Tags
	Route::apiResource('tags', 'TagController');
	Route::put('tags/status/{id}', 'TagController@activeOrInactive');
	Route::delete('tags/trash/{id}', 'TagController@moveToTrash');
	Route::patch('tags/restore/{id}', 'TagController@restoreFromTrash');
	Route::get('tags/export/file', 'TagController@export');

	// Pages
	Route::apiResource('pages', 'PageController');
	Route::put('pages/status/{id}', 'PageController@activeOrInactive');
	Route::delete('pages/trash/{id}', 'PageController@moveToTrash');
	Route::patch('pages/restore/{id}', 'PageController@restoreFromTrash');
	Route::get('pages/export/file', 'PageController@export');

	// Media
	Route::apiResource('media', 'MediaController');

	// Sliders
	Route::apiResource('sliders', 'SliderController');
	Route::get('sliders/sub/show/{category}', 'SliderController@subcategory');
	Route::put('sliders/status/{id}', 'SliderController@activeOrInactive');
	Route::delete('sliders/trash/{id}', 'SliderController@moveToTrash');
	Route::patch('sliders/restore/{id}', 'SliderController@restoreFromTrash');
	Route::get('sliders/export/file', 'SliderController@export');

	// Users
	Route::apiResource('users', 'UserController');
	Route::get('users/get/permissions', 'UserController@permissions');
	Route::put('users/status/{id}', 'UserController@activeOrInactive');
	Route::delete('users/trash/{id}', 'UserController@moveToTrash');
	Route::patch('users/restore/{id}', 'UserController@restoreFromTrash');
	Route::get('users/export/file', 'UserController@export');

	// Roles
	Route::apiResource('roles', 'RoleController');
	Route::put('roles/status/{id}', 'RoleController@activeOrInactive');
	Route::delete('roles/trash/{id}', 'RoleController@moveToTrash');
	Route::patch('roles/restore/{id}', 'RoleController@restoreFromTrash');
	Route::get('roles/export/file', 'RoleController@export');

	// Themes
	Route::apiResource('themes', 'ThemeController');

	// Reports
	//

	// Activity Log
	Route::apiResource('logs', 'LogController')->only('index');

	// Messages
	Route::apiResource('messages', 'MessageController')->only('index', 'show', 'destroy');
	Route::delete('messages/trash/{id}', 'MessageController@moveToTrash');
	Route::patch('messages/restore/{id}', 'MessageController@restoreFromTrash');
	Route::get('messages/export/file', 'MessageController@export');

	// Cache Management
	Route::post('artisan/cache-clear', 'ArtisanController@cache');
	Route::post('artisan/config-clear', 'ArtisanController@config');
	Route::post('artisan/view-clear', 'ArtisanController@view');
	Route::post('artisan/route-cache', 'ArtisanController@routeCache');
	Route::post('artisan/route-clear', 'ArtisanController@routeClear');
	

	// App Settings
	Route::apiResource('appSettings', 'AppSettingController');
	Route::put('appSettings/status/{id}', 'AppSettingController@activeOrInactive');
	Route::delete('appSettings/trash/{id}', 'AppSettingController@moveToTrash');
	Route::patch('appSettings/restore/{id}', 'AppSettingController@restoreFromTrash');
	Route::get('appSettings/export/file', 'AppSettingController@export');

	// App Setting Manifest
	Route::apiResource('appSetting/manufacture', 'AppSettingManufactureController');
	Route::put('appSetting/manufacture/setup/{id}', 'AppSettingManufactureController@setup');
	Route::put('appSetting/manufacture/status/{id}', 'AppSettingManufactureController@activeOrInactive');
	Route::delete('appSetting/manufacture/trash/{id}', 'AppSettingManufactureController@moveToTrash');
	Route::patch('appSetting/manufacture/restore/{id}', 'AppSettingManufactureController@restoreFromTrash');
	Route::get('appSetting/manufacture/export/file', 'AppSettingManufactureController@export');
});

