<?php

namespace App\Install;

use App\Models\AppSettings\Entities\AppSetting_manufacture;
use App\Models\AppSettings\Entities\AppSetting;
use App\Models\Categories\Entities\Category;
use App\Models\Pages\Entities\Page;
use App\Models\Posts\Entities\Post;
use Helper;
use DB;

class Setting
{
    public function setup($data)
    {
        $this->createDefaultPages($data);
        $this->createDefaultCategory();
        $this->createDefaultPost();
        $this->executeMigrations();
    }

    public function createDefaultPages($value)
    {   
        // Create Default Home Page
        $home = [
            'slug'             => '/',
            'title'            => 'Home',
            'body'             => NULL,
            // Metas
            'meta_title'       => $value['blog_name'],
            'meta_keywords'    => NULL,
            'meta_description' => NULL
        ];
        Page::createOrUpdate(NULL, NULL, NULL, $home);


        // Create Default Contacts Page
        $contacts = [
            'slug'             => 'contacts',
            'title'            => 'Contacts',
            'body'             => NULL,
            // Metas
            'meta_title'       => 'Contacts',
            'meta_keywords'    => NULL,
            'meta_description' => NULL
        ];
        Page::createOrUpdate(NULL, NULL, NULL, $contacts);


        // Create Default Privay Page
        $privacy = [
            'slug'             => 'privacy',
            'title'            => 'Privacy Policy',
            'body'             => NULL,
            // Metas
            'meta_title'       => 'Privacy Policy',
            'meta_keywords'    => NULL,
            'meta_description' => NULL
        ];
        Page::createOrUpdate(NULL, NULL, NULL, $privacy);

        // Activate All Pages
        Page::where(['status' => false])->update(['status' => true]);

            // feel free to add any new default pages...
    }

    public function createDefaultCategory()
    {
        // Create Default Category
        $category = [
            'parent_id'        => NULL,
            'slug'             => 'uncategorized',
            'name'             => 'Uncategorized',
            // Metas
            'meta_title'       => 'Uncategorized',
            'meta_keywords'    => NULL,
            'meta_description' => NULL
        ];
        Category::createOrUpdate(NULL, NULL, NULL, $category);

        // Activate default category
        Category::where(['status' => false])->update(['status' => true]);

            // feel free to add any new default categories...
    }

    public function createDefaultPost()
    {
        // Create Default Post
        $category = Category::first();
        $post = [
            'cat_id'           => $category->id,
            'subcat_id'        => 'null',
            'slug'             => 'hello-world',
            'title'            => 'Hello world!',
            'body'             => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            // Metas
            'meta_title'       => 'Hello world',
            'meta_keywords'    => NULL,
            'meta_description' => NULL
        ];
        Post::createOrUpdate(NULL, NULL, NULL, NULL, $post);

        // Publish default post
        Post::where(['status' => false])->update(['status' => true]);

            // feel free to add any new default posts...
    }

    public function executeMigrations()
    {
        
        // Execute AppSetting General Manifest
        $amp       = AppSetting::create(['app_name'=>'AMP', 'icon'=>'ti-google', 'setup'=>true]);
        $roles     = AppSetting::create(['app_name'=>'Roles', 'icon'=>'ti-lock', 'setup'=>true]);
        $theme     = AppSetting::create(['app_name'=>'Themes', 'icon'=>'ti-palette', 'setup'=>true]);
        $slider    = AppSetting::create(['app_name'=>'Sliders', 'icon'=>'ti-gallery', 'setup'=>true]);
        $language  = AppSetting::create(['app_name'=>'Language', 'icon'=>'ti-world', 'setup'=>true]);
        $logo      = AppSetting::create(['app_name'=>'Logo', 'icon'=>'ti-themify-logo', 'setup'=>true]);
        $timezone  = AppSetting::create(['app_name'=>'Timezone', 'icon'=>'ti-time', 'setup'=>false]);
        $report    = AppSetting::create(['app_name'=>'Reports', 'icon'=>'ti-stats-up', 'setup'=>false]);
        $aws       = AppSetting::create(['app_name'=>'AWS S3 Storage', 'icon'=>'ti-server', 'setup'=>true]);
        $logs      = AppSetting::create(['app_name'=>'Activity Logs', 'icon'=>'ti-brush-alt', 'setup'=>true]);
        $cache     = AppSetting::create(['app_name'=>'Cache Managements', 'icon'=>'ti-pulse', 'setup'=>true]);
        $social    = AppSetting::create(['app_name'=>'Social Media', 'icon'=>'ti-facebook', 'setup'=>true]);
        $sms       = AppSetting::create(['app_name'=>'SMS Configuration', 'icon'=>'ti-mobile', 'setup'=>false]);
        $email     = AppSetting::create(['app_name'=>'Email Configuration', 'icon'=>'ti-email', 'setup'=>false]);
        $media     = AppSetting::create(['app_name'=>'Media', 'icon'=>'ti-cloud_up', 'setup'=>true]);
        $analytics = AppSetting::create(['app_name'=>'Google Analytics', 'icon'=>'ti-stats-up', 'setup'=>false]);
        $favicon   = AppSetting::create(['app_name'=>'Favicon', 'icon'=>'ti-themify-favicon-alt', 'setup'=>false]);
        $bitbucket = AppSetting::create(['app_name'=>'Bitbucket', 'icon'=>'ti-archive', 'setup'=>false]);
        $code      = AppSetting::create(['app_name'=>'Code', 'icon'=>'ti-shortcode', 'setup'=>false]);
       

        // defind the main array
        $manufacture   = [];

        // Language
        $manufacture[] = ['app_id' => $language->id, 'index' => 'default_locale', 'value' => 'en'];

        // TimeZone
        $manufacture[] = ['app_id' => $timezone->id, 'index' => 'default_timezone', 'value' => 'UTC'];

        // Cache
        $manufacture[] = ['app_id' => $cache->id, 'index' => 'default_hours', 'value' => '24'];

        // AWS
        $manufacture[] = ['app_id' => $aws->id, 'index' => 'AWS_ACCESS_KEY_ID', 'value' => '#'];
        $manufacture[] = ['app_id' => $aws->id, 'index' => 'AWS_SECRET_ACCESS_KEY', 'value' => '#'];
        $manufacture[] = ['app_id' => $aws->id, 'index' => 'AWS_DEFAULT_REGION', 'value' => '#'];
        $manufacture[] = ['app_id' => $aws->id, 'index' => 'AWS_BUCKET', 'value' => '#'];

        // Slider
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'autoplay', 'value' => "on"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'autoplay_speed', 'value' => '400ms'];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'arrows', 'value' => "on"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'dots', 'value' => "on"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'width', 'value' => "100%"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'height', 'value' => "400px"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'font-color', 'value' => "white"];
        $manufacture[] = ['app_id' => $slider->id, 'index' => 'autoload_from_posts', 'value' => "on"];

        // Logo
        $manufacture[] = ['app_id' => $logo->id, 'index' => 'logo-header','value' =>'/themes/mitte/assets/images/logo.png'];
        $manufacture[] = ['app_id' => $logo->id, 'index' => 'logo-footer','value' =>'/themes/mitte/assets/images/logo.png'];

        // Favicons
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'shortcut icon', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-16x16', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-32x32', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-48x48', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-96x96', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-128x128', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'favicon-196x196', 'value' => ''];

        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-57x57', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-72x72', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-76x76', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-114x114', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-120x120', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-144x144', 'value' => ''];
        $manufacture[] = ['app_id' => $favicon->id, 'index' => 'apple-touch-icon-152x152', 'value' => ''];

        // Socials
        $manufacture[] = ['app_id' => $social->id, 'index' => 'facebook', 'value' => '#'];
        $manufacture[] = ['app_id' => $social->id, 'index' => 'twitter', 'value' => '#'];
        $manufacture[] = ['app_id' => $social->id, 'index' => 'instagram', 'value' => '#'];


        // Media
        $manufacture[] = ['app_id' => $media->id, 'index' => 'image-lg', 'value' => '1024*568'];
        $manufacture[] = ['app_id' => $media->id, 'index' => 'image-md', 'value' => '527*368'];
        $manufacture[] = ['app_id' => $media->id, 'index' => 'image-sm', 'value' => '132*132'];

            // feel free to add default settings as much as u want..
        
        // insert all the above into Manufactures
        AppSetting_manufacture::insert($manufacture);
    }

}
