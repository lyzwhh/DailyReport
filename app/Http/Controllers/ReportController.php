<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ReportService;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'tag1' => 'required'
        ]);
        $reportInfo = $request->all();
        $time = Carbon::now();
        $reportInfo['date']=$time->toDateString();
        $report = $this->reportService->getByDateUser($reportInfo['date'],$reportInfo['user']->id);
        if($report)
        {
            $this->reportService->update($reportInfo,$report->id);
        }
        else
        {
            $this->reportService->add($reportInfo);
        }
        return response([
            'message' => '成功添加日报',
            'code' => 0
        ]);
    }

    public function getByDate(string $date)
    {
        return response([
            'reports'   =>  $this->reportService->getByDate($date),
            'code'  =>  0
        ]);
    }

    public function getByUser(string $name,int $limit,int $offset)
    {
        return response([
            'reports'   =>  $this->reportService->getByUser($name,$limit,$offset),
            'code'  =>  0
        ]);
    }

    public function getDateSegmentation(string $date)
    {
        $reports = $this->reportService->getByDate($date);
        $segmentation = $this->reportService->wordSegmentation($reports);
        return response([
            'data' => $segmentation,
            'code' => 0
        ]);
    }

}
