<?php
namespace App\Http\Controllers;

use App\Services\OcupacionService;

class AdminController extends Controller
{
    public function __construct(
        private OcupacionService $ocupacionService
    ) {}

    public function dashboard()
    {
        return view('admin.dashboard.index', $this->ocupacionService->getDashboardData());
    }
}
