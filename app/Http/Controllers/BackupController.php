<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    public function run()
    {
        Artisan::call('db:backup');
        return redirect()->back();
    }

}
