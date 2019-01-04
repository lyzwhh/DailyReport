<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ReportService;
use App\Services\UserService;

class ReportController extends Controller
{
    private $reportService;
    private $userService;

    public function __construct(ReportService $reportService,
                                UserService $userService)
    {
        $this->reportService = $reportService;
        $this->userService = $userService;
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
        return response()->json([
            'message' => '成功添加日报',
            'code' => 0
        ]);
    }

    public function getByDate(string $date)
    {
        $reports = $this->reportService->getByDate($date);
        foreach ($reports as $report)
        {
            $report->nickname = $this->userService->getNickNameByID($report->user_id)[0];
        }
        return response([
            'data'   =>  $reports,
            'code'  =>  0
        ]);
    }

    public function getByUser(string $name,int $limit,int $offset)
    {
        return response([
            'data'   =>  $this->reportService->getByUser($name,$limit,$offset),
            'code'  =>  0
        ]);
    }

    public function getDateSegmentation(string $date)
    {
        $reports = $this->reportService->getByDate($date);
        $segmentation = $this->reportService->wordSegmentation($reports);
        arsort($segmentation);
        return response([
            'data' => $segmentation,
            'code' => 0
        ]);
    }

    public function getWeeklySegmentation()
    {
        return response([
            'data'  =>  $this->reportService->getWeeklySegmentation(),
            'code'  =>0
        ]);
    }

}
