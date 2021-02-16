<?php

namespace Hellotreedigital\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hellotreedigital\Cms\Models\CmsLog;

class CmsLogsController extends Controller
{
    public function index()
    {
        $rows = CmsLog::get();
        return view('cms::pages/logs/index', compact('rows'));
    }
}
