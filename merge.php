<?php
/**
 * Created by PhpStorm.
 * User: hey_j
 * Date: 2019/2/23
 * Time: 下午2:24
 */
//ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
$dir = './data/merge_data.txt';
$dir_money = './data/shop_data.txt';
$dir_wiki = './data/wiki_data.txt';
$dir_home = './data/nintendo_home.txt';

$data_1 = file_get_contents($dir_money);
$data_2 = file_get_contents($dir_wiki);
$data_3 = file_get_contents($dir_home);
if(!$data_1 || !$data_2){
    echo '錯誤';
    echo '$dir_money:'.var_dump($data_1,true);
    echo '$dir_wiki:'.var_dump($data_2,true);
    echo '$dir_wiki:'.var_dump($data_3,true);
    DIE;
}
$data_money = json_decode($data_1, true);
$data_wiki = json_decode($data_2, true);
$data_home = json_decode($data_3, true);

$ex_name = Array("Pokemon: Let's Go, Pikachu!","Pokemon: Let's Go, Eevee!");

$data = Array();
if($data_1 && $data_2 && $data_3){
    echo '$data_money 總共資料:'.count($data_money).'<br>';
    echo '$data_wiki 總共資料:'.count($data_wiki).'<br>';
    echo '$data_home 總共資料:'.count($data_home).'<br>';


//    var_dump($data_home,true);

    foreach($data_money as $money){
//        $save = '';
//        echo '$money[title]:' . strtoupper($money['title']) . '<br>';
//        echo '$data_wiki[title]:' . strtoupper($data_wiki[$i]['game_en']) . '<br>';
//        echo '$data_wiki[title]:' . strtoupper($data_wiki[$i]['game']) . '<br>';

        for($i = 0; $i < count($data_home);$i++) {

            for($j=0;$j < count($data_home[$i]);$j++){

//                if ((count(explode(strtoupper($money['title']), strtoupper($data_home[$i][$j]['title']))) > 1 )){
                if ($money['title'] == $data_home[$i][$j]['title']) {

                    echo '$money[title]:'.$money['title'].'<br>';
                    echo 'game $data_home:'.$data_home[$i][$j]['title'].' conut'.count(explode(strtoupper($money['title']), strtoupper($data_home[$i][$j]['title']))).'<br>';

                    array_push($money, $data_home[$i][$j]);
//                    array_merge((array)$money, (array)$data_home[$i][$j]);
//                    array_merge($data, $money);
//                    $save = 'ok';

                    break;
                }

            }

        }


        for($i = 0; $i < count($data_wiki);$i++) {

//            echo '$money[title]:' . strtoupper($money['title']) . '<br>';
//            echo '$data_wiki[game_en]:' . strtoupper($data_wiki[$i]['game_en']) . '<br>';
//            echo '$data_wiki[game]:' . strtoupper($data_wiki[$i]['game']) . '<br>';
//
            if ((count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game_en']))) > 1) ||
                (count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game']))) > 1) ||
                in_array(trim($money['title']),$ex_name)) {
//                echo '$money[title]:' . $money['title'] . '<br>';
                echo 'game_en:' . $data_wiki[$i]['game_en'] . ' conut' . count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game_en']))) . '<br>';
                echo 'game $data_wiki:' . $data_wiki[$i]['game'] . ' conut' . count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game']))) . '<br>';

                array_push($money, $data_wiki[$i]);
//                array_merge( (array)$money, (array)$data_wiki[$i]);
//                array_merge( (array)$data,  (array)$money);
//                $save = 'ok';
                break;
            }

        }




//        if($save !== 'ok'){
//            array_merge( (array)$data,  (array)$money);
            array_push($data, $money);
//        }else{
//            array_push($data, $money);
//        }


    }

//    foreach($data_money as $money){
////        for($j = 0; $j < count($data_money);$j++){
//        $save = '';
//            for($i = 0; $i < count($data_wiki);$i++) {
//
//                if ((count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game_en']))) > 1 )||
//                    (count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game']))) > 1)) {
//
////                    echo '一樣:'.count(explode($data_money[$j]['title'], $data_wiki[$i]['game_en'])).'<br>';
////                    echo '一樣:'.count(explode($data_money[$j]['title'], $data_wiki[$i]['game'])).'<br>';
//                    echo '$money[title]:'.$money['title'].'<br>';
//                    echo 'game_en :'.$data_wiki[$i]['game_en'].' conut'.count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game_en']))).'<br>';
//                    echo 'game    :'.$data_wiki[$i]['game'].' conut'.count(explode(strtoupper($money['title']), strtoupper($data_wiki[$i]['game']))).'<br>';
//
////                    foreach($data_wiki[$i] as $_wiki){
////                        array_push($money, $_wiki);
////                    }
//
//                    array_push($money, $data_wiki[$i]);
//                    array_push($data, $money);
//                    $save = 'ok';
//                }
//
//
//
//            }
//            if($save !== 'ok'){
//                array_push($data, $money);
//            }
//
//    }

    file_put_contents($dir, json_encode($data));
//    echo json_encode($data);
}else{

    echo '無資料';
}
?>

