<?php

use Illuminate\Support\Facades\Route;

/** Installation **/
Route::get('install/pre-installation', 'InstallController@preInstallation');
Route::get('install/configuration', 'InstallController@getConfiguration');
Route::post('install/configuration', 'InstallController@postConfiguration');
Route::get('install/complete', 'InstallController@complete');


/** Backend **/
Route::get('/dashboard/{path}', 'backend\DashController@index')->where('path', '.*');



/** Frontend **/

// Generate Sitemap
Route::get('sitemap.xml', 'SitemapController@index');

// Home
Route::get('/', 'PageController@home')->name('home');
Route::get('amp', 'PageController@home')->name('amp');
Route::get('/home/render', 'PageController@render');
Route::get('/home/trends', 'PageController@trends');

// Posts
Route::get('post/{post}', 'PostController@show');

// Categories
Route::get('category/{category}', 'CategoryController@index');
Route::get('category/{category}/render', 'CategoryController@render');

// Tags
Route::get('tag/{tag}', 'TagController@index');
Route::get('tag/{tag}/render', 'TagController@render');

// Search
Route::get('search', 'SearchController@index');
Route::get('search/render', 'SearchController@render');

// Pages
Route::get('contacts', 'PageController@contacts');
Route::post('contacts', 'PageController@doContacts');
Route::get('{page}', 'PageController@show');


