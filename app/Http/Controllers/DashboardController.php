<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use Mpdf\Tag\Em;
use Spatie\Permission\Models\Role;
use DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @access public
     * @return mixed
     */
    public function index()
    {
        return view('dashboardview');
    }
}
