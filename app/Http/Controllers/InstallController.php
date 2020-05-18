<?php

namespace App\Http\Controllers;

use Exception;
use DotenvEditor;
use App\Install\App;
use App\Install\Setting;
use App\Install\Database;
use App\Install\Requirement;
use App\Install\AdminAccount;
use App\Http\Requests\InstallRequest;
use App\Http\Middleware\RedirectIfInstalled;

class InstallController extends Controller
{

    public function __construct()
    {
        $this->middleware(RedirectIfInstalled::class);
    }

    public function preInstallation(Requirement $requirement)
    {
        return view('install.pre_installation', compact('requirement'));
    }

    public function getConfiguration(Requirement $requirement)
    {
        if (! $requirement->satisfied()) {
            return redirect('install/pre-installation');
        }

        return view('install.configuration', compact('requirement'));
    }


    public function postConfiguration(
        InstallRequest $request,
        Database $database,
        AdminAccount $admin,
        Setting $setting,
        App $app
    ) {
        set_time_limit(3000);

        try {
            $database->setup($request->db);
            $admin->setup($request->admin);
            $setting->setup($request->blog);
            $app->setup();
        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect('install/complete');
    }

    public function complete()
    {
        if (config('app.installed')) {
            return redirect()->route('home');
        }

        DotenvEditor::setKey('APP_INSTALLED', 'true')->save();

        return view('install.complete');
    }

}
