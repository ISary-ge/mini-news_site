<?php
$random = false; // рандомный показ тизеров
$internal_articles = file_get_contents("internal_articles.txt");
$internal_arr = json_decode($internal_articles, true);

$external_articles = file_get_contents("external_articles.txt");
$external_arr = json_decode($external_articles, true);

$periodicity = 3; // частота показа внешних тизеров

shuffle($internal_arr);

if($random){
    shuffle($external_arr);
}

$all_articles = [];
$j = 0;

$params = $_GET;
unset($params['id']);
unset($params['video']);