<?php
/**
 * Created by PhpStorm.
 * User: hey_j
 * Date: 2019/2/27
 * Time: 上午9:55
 */
$dir = './data/nintendo_home.txt';
$url_1 = 'https://www.nintendo.com/json/content/get/filter/game?limit=40&offset=0&system=switch&sort=release&direction=des';
$result = array();
$contents_1 =_curl($url_1);
$data_1 = json_decode($contents_1, true);
//總數
$total = $data_1['filter']['total'];
//第一次
//$result= $data_1['games']['game'];
$result['total'] =  $total;
$i = 0;
do{
    $url_2 = 'https://www.nintendo.com/json/content/get/filter/game?limit=40&offset='.$i.'&system=switch&sort=release&direction=des';
    $contents_2 =_curl($url_2);
    $data_2 = json_decode($contents_2, true);
    array_push($result, $data_2['games']['game']);
    echo '第'.$i.'抓 url:'.$url_2."<br>";
    echo '總共:'.count($result)."<br>";

    $i = $i + 40;
}while($i<$total);

file_put_contents($dir, json_encode($result));


/**
 * Scott
 * 抓取 curl
 */
function _curl($_url){
    $ch=curl_init();
    // $this_header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
    curl_setopt($ch, CURLOPT_URL , $_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
    curl_setopt($ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows; U; Windows NT 5.1; )');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
    $contents=curl_exec($ch );
    $contents = mb_convert_encoding($contents, 'GB2312', 'GBK, UTF-8, ASCII , U+00E9');
    curl_close( $ch);
    return $contents;
}



?>