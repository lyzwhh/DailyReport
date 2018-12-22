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
class PassService
{
    public function getThatDayReport($info)
    {
        $report = DB::table('reports')
            ->where('date',$info['date'])->where('user_id',$info['user']->id)->first();  //this id is user_id
        return $report;
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

}