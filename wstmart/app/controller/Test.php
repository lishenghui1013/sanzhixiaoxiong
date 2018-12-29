<?php
namespace wstmart\app\controller;
/*
 * 测试类
 */
use think\Db;
use think\Log;

header('Access-Control-Allow-Origin:*');
class Test extends Base {

    /**
     * 求一个数的平方
     * @param $n
     */
    function sqr($n){
        return $n*$n;
    }

    /**
     * 生产min和max之间的随机数，但是概率不是平均的，从min到max方向概率逐渐加大。
     * 先平方，然后产生一个平方值范围内的随机数，再开方，这样就产生了一种“膨胀”再“收缩”的效果。
     */
    function xRandom($bonus_min,$bonus_max){
        $sqr = intval($this->sqr($bonus_max-$bonus_min));
        $rand_num = rand(0, ($sqr-1));
        return intval(sqrt($rand_num));
    }


    /**
     *
     * @param $bonus_total 红包总额
     * @param $bonus_count 红包个数
     * @param $bonus_max 每个小红包的最大额
     * @param $bonus_min 每个小红包的最小额
     * @return 存放生成的每个小红包的值的一维数组
     */
    function getBonus($bonus_total, $bonus_count, $bonus_max, $bonus_min) {
        $result = array();

        $average = $bonus_total / $bonus_count;

        $a = $average - $bonus_min;
        $b = $bonus_max - $bonus_min;

        //
        //这样的随机数的概率实际改变了，产生大数的可能性要比产生小数的概率要小。
        //这样就实现了大部分红包的值在平均数附近。大红包和小红包比较少。
        $range1 = $this->sqr($average - $bonus_min);
        $range2 = $this->sqr($bonus_max - $average);

        for ($i = 0; $i < $bonus_count; $i++) {
            //因为小红包的数量通常是要比大红包的数量要多的，因为这里的概率要调换过来。
            //当随机数>平均值，则产生小红包
            //当随机数<平均值，则产生大红包
            if (rand($bonus_min, $bonus_max) > $average) {
                // 在平均线上减钱
                $temp = $bonus_min + $this->xRandom($bonus_min, $average);
                $result[$i] = $temp;
                $bonus_total -= $temp;
            } else {
                // 在平均线上加钱
                $temp = $bonus_max - $this->xRandom($average, $bonus_max);
                $result[$i] = $temp;
                $bonus_total -= $temp;
            }
        }
        // 如果还有余钱，则尝试加到小红包里，如果加不进去，则尝试下一个。
        while ($bonus_total > 0) {
            for ($i = 0; $i < $bonus_count; $i++) {
                if ($bonus_total > 0 && $result[$i] < $bonus_max) {
                    $result[$i]++;
                    $bonus_total--;
                }
            }
        }
        // 如果钱是负数了，还得从已生成的小红包中抽取回来
        while ($bonus_total < 0) {
            for ($i = 0; $i < $bonus_count; $i++) {
                if ($bonus_total < 0 && $result[$i] > $bonus_min) {
                    $result[$i]--;
                    $bonus_total++;
                }
            }
        }
        return $result;
    }
    public function index(){
        print_r(base64_encode("{'typ': 'JWT','alg': 'HS256'}"));
        print_r(base64_decode("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9"));
//        dump(input('post.'));
//        $ret = 666;
//        if (!is_array($ret)) {
//            return 99999;
//        }else{
//            return 66666;
//        }
//
          die();
//        Log::write();

        //第一个
//        $bonus_total = 20;
//        $bonus_count =30;
//        $bonus_max = 10;//此算法要求设置的最大值要大于平均值
//        $bonus_min = 0.01;
//        $result_bonus = $this->getBonus($bonus_total, $bonus_count, $bonus_max, $bonus_min);
//        $total_money = 0;
//        $arr = array();
//        foreach ($result_bonus as $key => $value) {
//        $total_money += $value;
//        if(isset($arr[$value])){
//        $arr[$value] += 1;
//        }else{
//            $arr[$value] = 1;
//        }
//
//        }
//        //输出总钱数，查看是否与设置的总数相同
//        echo $total_money;
//        //输出所有随机红包值
//        dump($result_bonus);
//        //统计每个钱数的红包数量，检查是否接近正态分布
//        ksort($arr);
//        dump($arr);

        //第二个
      /*  $total=1;//红包总金额
        $num=50;// 分成10个红包，支持10人随机领取
        $min=0.01;//每个人最少能收到0.01元
//        $redpack = new redpack($total,$num,$min);
        $jieguo = $this->getPack($total,$num,$min);
        foreach($jieguo as $key=>$val){
            $n = $key+1;
            echo '第'.$n.'个红包：'.$val['money'].' 元，余额：'.$val['balance'].' 元<br>';
        }*/

        /*第三个*/
        $money = round(@1, 2) ?: 0;    // 红包大小
        $num   = intval(@99) ?: 1;      // 红包个数
        $min   = round(@0, 2) ?: 0.01; // 单个红包最低金额
//        print_r($min);die;

        $ret = $this->lottery($money, $num, $min);
        if (! is_array($ret)) {
            exit($ret.PHP_EOL);
        }

        echo '手气最佳：'.max($ret).PHP_EOL;
        echo '手气最差：'.min($ret).PHP_EOL;
        echo PHP_EOL;

        print_r($ret);
    }




    //第二个 示例
    //总金额
    private $total=0;
    //红包数量
    private $num=0;
    //最小红包金额
    private $min=0.01;

//    public function __construct($total,$num,$min)
//    {
//        $this->total = $total;
//        $this->num = $num;
//        $this->min = $min;
//    }
    //红包结果
    public function getPack($total,$num,$min)
    {
//        $total = $this->total;
//        $num = $this->num;
//        $min = $this->min;
        for ($i=1;$i<$num;$i++)
        {
            $safe_total=($total-($num-$i)*$min)/($num-$i);//随机安全上限
            $money=mt_rand($min*100,$safe_total*100)/100;
            $total=$total-$money;
            //红包数据
            $readPack[]= [
                'money'=>$money,
                'balance'=>$total,
            ];
        }
        //最后一个红包，不用随机
        $readPack[] = [
            'money'=>$money,
            'balance'=>0,
        ];
        //返回结果
        return $readPack;
    }


    /*第三个*/
    // 产生一个随机浮点数
    function random_float($min = 0, $max = 1)
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
    // 微信随机红包模拟算法
    function lottery($sum_money, $num, $min_money = 0.01)
    {
        if ($sum_money < $num * $min_money) {
            return '老板，钱不够发';
        }

        $list = [];
        for ($i = 1; $i <= $num; $i++) {
            // 剩余的可分配金额，需要确保剩下的人每人都至少可以拿到保底的钱
            $remain = $sum_money - array_sum($list) - ($num - $i + 1) * $min_money;

            if ($i < $num) {  // 前面的人随机获得
                // 每轮抽取的金额范围：0 至 剩余金额平均值的两倍
                $get = $this->random_float(0, $remain / ($num - $i + 1) * 2);
            } else {  // 最后一个人拿全部剩下的
                $get = $remain;
            }

            // 最后再将每个人保底的钱加上
            $list[] = round(round($get, 2) + $min_money, 2);
        }

        return $list;
    }

    /*经纬度 查询附近红包测试*/
    /*
     *参数说明：
     *$lng  经度
     *$lat   纬度
     * $distance  周边半径  默认是500米（0.5Km）
     */
    public function returnSquarePoint($lng, $lat,$distance = 1.2) {
        $dlng =  2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/6371;//地球半径，平均半径为6371km
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );

    }
    //接收起点经纬度 $longitude, $latitude
    public function Distance() {
        $longitude=input('post.longitude');
        $latitude=input('post.latitude');
        $array = $this->returnSquarePoint($longitude, $latitude);
        $map = array(
            'latitude' => array(array('EGT',$array['right-bottom']['lat']),array('ELT',$array['left-top']['lat']),'and'),
            'longitude' => array(array('EGT',$array['left-top']['lng']),array('ELT',$array['right-bottom']['lng']),'and'),
        );
        $basic_equipment = db("hongbao");
        $data = $basic_equipment->where($map)->select();
//        echo $basic_equipment->getLastSql(); die(); //打印thinkPHP中对SQL语句
        dump($data);die();

//        return $data;
    }






}