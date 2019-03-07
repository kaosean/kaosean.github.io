<?php
/**
 * Created by PhpStorm.
 * User: hey_j
 * Date: 2019/3/6
 * Time: 上午11:05
 */
//ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
$dir = './data/special.txt';
$dir_merge = './data/merge_data.txt';

$data_1 = file_get_contents($dir_merge);
if(!$data_1){
    echo '錯誤';
    echo '$dir_merge:'.var_dump($data_1,true);
    DIE;
}
$dir_merge = json_decode($data_1, true);


$data = Array();
if($data_1){
    echo '$data_money 總共資料:'.count($dir_merge).'<br>';

    foreach($dir_merge as $merge){
//        echo '$merge[max_price]:' . $merge['max_price'] . '<br>';
//        echo '$merge[min_price]:' . $merge['min_price'] . '<br>';

        if($merge['differ']!=0){
            echo '$merge[percent]:' . $merge['percent'] . '<br>';
            array_push($data, $merge);
        }
    }


    $j=0;
    $flag = true;
    $temp=0;

    while ( $flag )
    {
        $flag = false;
        for( $j=0;  $j < count($data)-1; $j++)
        {
            if ( $data[$j]["percent"] > $data[$j+1]["percent"] )
            {
                $temp = $data[$j];
                //swap the two between each other
                $data[$j] = $data[$j+1];
                $data[$j+1]=$temp;
                $flag = true; //show that a swap occurred
            }
        }
    }




    file_put_contents($dir, json_encode($data));
//    echo json_encode($data);
}else{

    echo '無資料';
}


function method1($a,$b)
{
    return ($a[2]["sizes"]["weight"] <= $b[2]["sizes"]["weight"]) ? -1 : 1;
}
usort($array, "method1");




?>