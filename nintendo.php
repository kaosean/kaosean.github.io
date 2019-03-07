<?php
$dir = './data/shop_data.txt';

$ch=curl_init();
curl_setopt( $ch, CURLOPT_URL , 'https://eshop-prices.com/prices?currency=USD');
curl_setopt( $ch , CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER , 1);
curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows; U; Windows NT 5.1; )');
$contents=curl_exec($ch );
curl_close($ch);
$contents = mb_convert_encoding($contents, 'UTF-8', 'GBK, UTF-8, ASCII');

// echo $contents;
// file_put_contents('./data/raw.txt', $contents);

$first_pattern = '/<table data-search-table=true>[\w\s\d\W]*<\/table>/';
preg_match_all($first_pattern,$contents,$first_content);




$dom = new domDocument;
$dom->loadHTML(mb_convert_encoding($first_content[0][0], 'HTML-ENTITIES', 'UTF-8'));
$dom->preserveWhiteSpace = false;
if (!empty($dom)) {
    $tags = $dom->getElementsByTagName('thead');
    $tags2 = $dom->getElementsByTagName('tr');
} else {
    // return self::failed(32, '取内容异常：'. '$contents:'.$first_content[0]);
}

// echo $first_content[0][0];

$title = Array();
$data = Array();
for ($i=0; $i<$tags2->length;$i++) {
    $check = $tags2->item($i);
    if (!empty($check)) {

        // echo '第'.$i.'次'."<br>";

        $tds_1 = $tags2->item($i)->getElementsByTagName('th');
        $tds_2 = $tags2->item($i)->getElementsByTagName('td');

        // var_dump( $tds_2);
        if($i < 1){
            for ($j=0; $j <$tds_1->length; $j++) {

                array_push($title, $tds_1->item($j)->nodeValue);
                // echo 'title:'.$title[$j]."<br>";
                // echo '價錢:'.$cost_total[$j]."\n";
            }
        }

        $cost = Array();
        $cost_total = Array();
        for ($j=0; $j <$tds_2->length; $j++) {

            array_push($cost, str_replace("$","", ($tds_2->item($j)->nodeValue)));
            array_push($cost_total, str_replace("N/A","0", $cost[$j]));

        }



        $USD = 30.76;
        $td1 = trim($tds_1->item(0)->nodeValue);
        $td1_fix = str_replace("Ã©", "é", trim($td1));
//        $td1_fix = str_replace("Pokemon: Let's Go, Eevee!", "Let's Go, Eevee!", $td1_fix);
//        $td1_fix = str_replace("Pokemon: Let's Go, Pikachu!", "Let's Go, Pikachu!", $td1_fix);
        $data_t['title'] = $td1_fix;
        $data_t['max_loc'] = trim($title[array_search((max($cost_total)),$cost_total) + 1 ]);
        $data_t['max_price'] = round(trim(MAX($cost_total))*$USD);
        $data_t['min_loc'] = trim($title[array_search((min(array_filter($cost_total))),$cost_total) + 1 ]);
        $data_t['min_price'] = round((trim(min(array_filter($cost_total))))*$USD);
        $data_t['differ'] = round((trim(MAX($cost_total)-min(array_filter($cost_total))))*$USD);
        $data_t['percent'] = round(($data_t['min_price'] / $data_t['max_price']) * 100);
        echo '遊戲名稱:'.$data_t['title'].', 最貴:'.$data_t['max_loc'].' '.$data_t['max_price'].
            ',  最便宜:'.$data_t['min_loc'] .' '.$data_t['min_price'].' 相差: $'.$data_t['differ'].
            ' 折價: '.$data_t['percent'].'%'."<br>";
        array_push($data,$data_t);
    }

}

file_put_contents($dir, json_encode($data));
//echo json_encode($data);

// }else{
// echo var_dump($debug,true);
//         header("location:http://lifunny.me/");
//         die;
// }




?>
