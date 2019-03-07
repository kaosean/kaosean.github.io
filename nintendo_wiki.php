<?php
//ini_set("display_errors", 1);
$dir = './data/wiki_data.txt';
$contents = _curl('https://zh.wikipedia.org/zh-tw/%E4%BB%BB%E5%A4%A9%E5%A0%82Switch%E6%B8%B8%E6%88%8F%E5%88%97%E8%A1%A8');
$first_pattern = '/<table class="wikitable sortable" style="width: 98%; font-size: small;">[\w\s\d\W]*<\/table>/U';
preg_match_all($first_pattern,$contents,$first_content);

$dom = new domDocument;
$dom->loadHTML(mb_convert_encoding($first_content[0][0], 'HTML-ENTITIES', 'UTF-8'));
$dom->preserveWhiteSpace = false;
if (!empty($dom)) {
    $tags = $dom->getElementsByTagName('tr');
} else {
    echo '異常:'.var_dump($first_content,true);
}


$title = Array();
$data = Array();
for ($i=2; $i<$tags->length;$i++) {
    $check = $tags->item($i);
    if (!empty($check)) {

        // echo '第'.$i.'次'."<br>";

        $tds = $tags->item($i)->getElementsByTagName('td');
        $td1 = $tds->item(0)->nodeValue;
        $td2 = $tds->item(1)->nodeValue;
        $td3 = $tds->item(2)->nodeValue;

        $tdsa = $tags->item($i)->getElementsByTagName('a');
        $tdsp = $tags->item($i)->getElementsByTagName('p');

//        $data_t['game'] = str_replace("é", "e", trim($td1));
        $data_t['game'] = trim($td1);
        $data_t['game_type'] = trim($td2);
        $data_t['game_dev'] = trim($td3);
        $data_t['game_tw'] = trim($tdsa->item(0)->nodeValue);
//        $data_t['game_tw'] = str_replace("é", "e", trim($tdsa->item(0)->nodeValue));

        if(!empty($tdsp->item(0))){
            $data_t['game_en'] =trim($tdsp->item(0)->nodeValue);
//            $data_t['game_en'] =str_replace("é", "e", trim($tdsp->item(0)->nodeValue));
        }


        if ($tdsa->item(0)->nodeType == 1) {
//            $data_t['game_href'] = 'https://zh.wikipedia.org'.trim($tdsa->item(0)->getAttribute('href'));
            $url = str_replace("wiki", "zh-tw", trim($tdsa->item(0)->getAttribute('href')));
            $data_t = getGameDetail($data_t,'https://zh.wikipedia.org'.$url);
        }

        echo '遊戲名字:'.$data_t['game']."\n <br>";
//        echo 'href:'.str_replace("wiki", "zh-tw", trim($tdsa->item(0)->getAttribute('href')))."\n <br>";
        echo '圖片下載位置:'.$data_t['game_cover']."\n <br>";
        echo "<br>";

        array_push($data,$data_t);
    }

}

file_put_contents($dir, json_encode($data));
//echo json_encode($data);


/**
 * 遊戲資訊
 * @param $data
 * @param $url
 */
function getGameDetail($data ,$url){
    $data['game_cover']= '';
    $contents = _curl($url);
//     file_put_contents('./game_inside.txt', $contents);

    $first_pattern = '/<table class="infobox" cellspacing="3" style="border-spacing:3px;width:22em;text-align:left;font-size:small;line-height:1.5em">[\w\s\d\W]*<\/table>/U';
    preg_match_all($first_pattern,$contents,$first_content);

    $dom = new domDocument;
    $dom->loadHTML(mb_convert_encoding($first_content[0][0], 'HTML-ENTITIES', 'UTF-8'));
    $dom->preserveWhiteSpace = false;

    if (!empty($dom)) {
        $tags = $dom->getElementsByTagName('tr');
    } else {
        echo '異常:'.var_dump($first_content,true);
    }
//    echo '圖片:'.'https://zh.wikipedia.org'.$tdsa->item(0)->getAttribute('href')."\n <br>";

    for ($i=3; $i<$tags->length;$i++) {
        $check = $tags->item($i);
        if (!empty($check)) {
            $tds = $tags->item($i)->getElementsByTagName('th');
            $td0 = $tds->item(0)->nodeValue;
            $tds = $tags->item($i)->getElementsByTagName('td');
            $td1 = $tds->item(0)->nodeValue;


            $data['game_d1']=$td0;
            $data['game_d2']=$td1;

        }

    }

    foreach($dom->getElementsByTagName('img') as $image){


//        echo '圖片:'.'https:'.$image->getAttribute('src')."<br>";
        $data['game_cover']='https:'.$image->getAttribute('src');
        break;
    }

//    if(!empty($tags->item(2))){
//        $tdsa = $tags->item(2)->getElementsByTagName('a');
//        if(!empty($tdsa->item(0)->nodeType == 1)){
//
//            $data = getGamePic($data,'https://zh.wikipedia.org'.$tdsa->item(0)->getAttribute('href'));
//        }
//    }


    return $data;
}

/**
 * 圖片資訊
 * @param $data
 * @param $url
 */
function getGamePic($data ,$url){
    $contents = _curl($url);
    $first_pattern = '/<div class="fullMedia">[\w\s\d\W]*<\/div>/U';
    preg_match_all($first_pattern,$contents,$first_content);

    $dom = new domDocument;
    $dom->loadHTML(mb_convert_encoding($first_content[0][0], 'HTML-ENTITIES', 'UTF-8'));
    $dom->preserveWhiteSpace = false;

    if (!empty($dom)) {
        $tags = $dom->getElementsByTagName('p');
    } else {
        echo '異常:'.var_dump($first_content,true);
    }

    for ($i=0; $i<$tags->length;$i++) {
        $check = $tags->item($i);
        if (!empty($check)) {

            $tdsa = $tags->item($i)->getElementsByTagName('a');


            if ($tdsa->item(0)->nodeType == 1) {
//                echo '$td4:'.'https:'.$tdsa->item(0)->getAttribute('href')."\n <br>";
                $data['game_cover']='https:'.$tdsa->item(0)->getAttribute('href');
            }


        }


    }

    return $data;
}

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
    $contents = mb_convert_encoding($contents, 'utf-8', 'GBK, UTF-8, ASCII');
    curl_close( $ch);
    return $contents;
}


?>
