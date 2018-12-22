<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\PassService;

class ReportController extends Controller
{
    private $passService;

    public function __construct(PassService $passService)
    {
        $this->passService = $passService;
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'tag1' => 'required'
        ]);
        $reportInfo = $request->all();
        $time = Carbon::now();
        $reportInfo['date']=$time->toDateString();
        $report = $this->passService->getThatDayReport($reportInfo);
        if($report)
        {
            $this->passService->update($reportInfo,$report->id);
        }
        else
        {
            $this->passService->add($reportInfo);
        }
        return response([
            'message' => '成功添加日报',
            'code' => 0
        ]);
    }
}
