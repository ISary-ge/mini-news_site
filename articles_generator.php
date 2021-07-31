<?php

require_once "get_articles.php";

for ($i = 0;$i<count($internal_arr);$i++){
    if(isset($_GET['id'])){
        if ($internal_arr[$i]['id'] == $_GET['id']){
            unset($internal_arr[$i]);
            continue;
        }
    }
    if($i % $periodicity == 0){ // частота показа тизеров
        array_push($all_articles, $external_arr[$j]);
        array_push($all_articles, $internal_arr[$i]);
        $j++;
    }else{
        array_push($all_articles, $internal_arr[$i]);
    }
}


?>

<?php foreach ($all_articles as $item) :?>
    <div class="col">
        <a href="<?php if(!empty($item['external_link'])){
            preg_match_all('/{([^}]+)}/',$item['external_link'], $macro);
            foreach ($macro[0] as $param){
                $param = str_replace(['{','}'],'',$param);
                if(isset($_GET[$param])){
                    $item['external_link'] = preg_replace("/{($param)}/",$_GET[$param],$item['external_link']);
                }else{
                    if($param == 'teaser_id'){
                        $item['external_link'] = preg_replace("/{($param)}/",$item['id'],$item['external_link']);
                    }else{
                        $item['external_link'] = preg_replace("/{($param)}/",'',$item['external_link']);
                    }
                }
            }
            echo $item['external_link'];
        }else{
            echo "personal_page.php?id=" . $item['id'];
        };?>" style="">
            <div class="card">
                <img src="<?=$item['img'];?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <p class="card-text"><?=$item['title'];?></p>
                </div>
            </div>
        </a>
    </div>
<?endforeach;?>
