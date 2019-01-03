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
use Fukuball\Jieba\JiebaAnalyse;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
class ReportService
{
    public function __construct()    //一个都不能少
    {
        JiebaAnalyse::init();
        Jieba::init(array('mode'=>'test','dict'=>'small'));
        Finalseg::init();
    }

    public function getByDateUser($date,$userId)  //one user
    {
        $report = DB::table('reports')
            ->where('date',$date)->where('user_id',$userId)->first();  //this id is user_id
        return $report;
    }

    public function getByDate($date)
    {
        $reports = DB::table('reports')
            ->where('date',$date)->get();
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
    public static function autoCountWeekly()
    {

    }

    public function wordSegmentation($reports)
    {
        $top_k = 10;

        //TODO: 优化算法
        $tags= [];
        foreach ($reports as $report)
        {
            if($report->tag1 != null)
            {
                $tags1 = JiebaAnalyse::extractTags($report->tag1, $top_k);// extractTags 函数被我·修改为·返回频数而不是TF-IDF
                foreach ($tags1 as $key => $value)
                {
                    if(empty($tags[$key]))
                    {
                        $tags[$key] = 0;
                    }
                    $tags[$key] += $value;
                }
            }
            if($report->tag2 != null)
            {
                $tags2 = JiebaAnalyse::extractTags($report->tag2, $top_k);
                foreach ($tags2 as $key => $value)
                {
                    if(empty($tags[$key]))
                    {
                        $tags[$key] = 0;
                    }
                    $tags[$key] += $value;
                }
            }
            if($report->tag3 != null)
            {
                $tags3 = JiebaAnalyse::extractTags($report->tag3, $top_k);
                foreach ($tags3 as $key => $value)
                {
                    if(empty($tags[$key]))
                    {
                        $tags[$key] = 0;
                    }
                    $tags[$key] += $value;
                }
            }


        }
        return $tags;

    }

}