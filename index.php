<?php
require_once "get_articles.php";

for ($i = 0;$i<count($internal_arr);$i++){
    if($i % $periodicity == 0){
        array_push($all_articles, $external_arr[$j]);
        array_push($all_articles, $internal_arr[$i]);
        $j++;
    }else{
        array_push($all_articles, $internal_arr[$i]);
    }
}
if(isset($_GET['video'])){
    $video_articles = file_get_contents("video_articles.txt");
    $video_arr = json_decode($video_articles, true);
    $video_article = [];
    foreach ($video_arr as $video){
        if($video['id'] == $_GET['video'])
        {
            $video_article = $video;
            break;
        }
    }
    if($video_article == []){
        unset($_GET['video']);
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Новости 24/7</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="src/sstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
    <header style="margin-bottom: 20px;">
        <nav class="navbar navbar-light bg-light">
            <a class="navbar-brand" href="">
                <img src="src/2logo.gif" class="d-inline-block align-top" alt="">
            </a>
        </nav>
    </header>
    <div class="container">
        <?php if(isset($_GET['video'])) :?>
            <div class="video_block" style="margin-bottom: 30px; max-width: 100%">
                <a href="<?php echo "personal_page.php?video=" . $video_article['id'];
                $personal_query = '';
                foreach ($params as $k=>$v){
                    $personal_query .= '&'. $k . '=' . $v;
                }
                echo $personal_query;?>">
                        <div class="card video">
                            <img src="<?=$video_article['img'];?>" class="card-img-top " alt="" style="object-fit: cover">
                            <img src="src/img/play.png" class="bg-play" alt="">
                            <div class="card-body">
                                <h2 class="card-text"><?=$video_article['title'];?></h2>
                            </div>
                        </div>
                    </a>
                </div>
        <?endif;?>

        <div class="row row-cols-1 row-cols-md-3 g-4">
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
                        $personal_query = '';
                        foreach ($params as $k=>$v){
                            $personal_query .= '&'. $k . '=' . $v;
                        }
                        echo $personal_query;
                    };?>" style="">
                        <div class="card">
                            <img src="<?=$item['img'];?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h2 class="card-text"><?=$item['title'];?></h2>
                            </div>
                        </div>
                    </a>
                </div>
            <?endforeach;?>

        </div>
        <script>
            window.addEventListener('scroll', async function (){
                let windowRelativeBottom = document.documentElement.getBoundingClientRect().bottom;
                // если пользователь прокрутил достаточно далеко (< 100px до конца)
                if (windowRelativeBottom < document.documentElement.clientHeight+100) {
                    let response = await fetch("articles_generator.php<?php $query = '?' . $params[0] ;
                        foreach ($params as $k=>$v){
                            $query .= '&'. $k . '=' . $v;
                        }
                        echo $query;?>");
                    if(response.ok){
                        let row = document.querySelector(".row");
                        let res = await response.text();
                        row.insertAdjacentHTML('beforeend', res);

                    }
                }
            })
        </script>
    </div>

</body>
</html>