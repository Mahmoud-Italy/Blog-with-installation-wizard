<?php

namespace App\Install;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class App
{
    public function setup()
    {
        $this->generateAppKey();
        $this->setEnvVariables();
        $this->copyHtaccessFile();
    }

    private function generateAppKey()
    {
        Artisan::call('key:generate', ['--force' => true]);
    }

    private function setEnvVariables()
    {
        $env = DotenvEditor::load();

        $env->setKey('APP_ENV', 'production');
        $env->setKey('APP_DEBUG', 'false');
        $env->setKey('APP_CACHE', 'true');
        $env->setKey('APP_URL', url('/'));

        $env->save();
    }

    private function copyHtaccessFile()
    {
        File::copy(base_path('.htaccess.example'), base_path('.htaccess'));
    }
}
