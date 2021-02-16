<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\Log;

class LogsController extends Controller
{
    public function index()
    {
        $rows = Log::get();
        return view('cms::pages/logs/index', compact('rows'));
    }
}
