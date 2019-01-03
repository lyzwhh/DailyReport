<?php
/**
 * Created by PhpStorm.
 * User: yuse
 * Date: 2018/12/22
 * Time: 10:30
 */

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ReportService
{
    public function getByDateUser($date,$userId)  //one user
    {
        $report = DB::table('reports')
            ->where('date',$date)->where('user_id',$userId)->first();  //this id is user_id
        return $report;
    }

    public function getByDay($day)
    {
        $reports = DB::table('reports')
            ->where('date',$day)->get();
        return $reports;
    }

    public function getByUser($name,$limit,$offset)
    {
        $id = DB::table('users')
            ->where('name',$name)
            ->pluck('id');
        $reports = DB::table('reports')
            ->orderBy('date', 'desc')
            ->where('user_id',$id)
            ->offset($offset)
            ->limit($limit)
            ->get();
        return $reports;
    }

    public function add($info)
    {
        $info['user_id'] = $info['user']->id;
        unset($info['user']);
        DB::table('reports')->insert($info);
    }

    public function update($info,$id)
    {
        unset($info['user']);
        DB::table('reports')->where('id',$id)->update($info);
    }

    public static function autoLoafing(string $day)
    {
        $allMember = DB::table('users')->select('id')->get();
        foreach ($allMember as $member)
        {
            $report = DB::table('reports')
                ->where('date',$day)->where('user_id',$member->id)->first();
            if($report == null)
            {
                $data = [
                    'user_id' => $member->id,
                    'date' => $day
                ];
                DB::table('reports')->insert($data);
            }
        }
    }

}