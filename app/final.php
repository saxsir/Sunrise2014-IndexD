<?php

$app->get('/',function() use($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/info',function() use($app) {
    return phpinfo();
});

$app->get('/get_timeline',function() use($app) {
    $con = $app['db'];
    // シンプルだけど遅いクエリ
    // cf. http://www.atmarkit.co.jp/news/201004/19/twitter.html
    // $sql = 'SELECT id, body FROM tweets WHERE user_id IN (SELECT source_id FROM follows WHERE destination_id = ?) ORDER BY created_at DESC LIMIT 20)';
    $sql = 'SELECT id, body FROM tweets WHERE user_id IN (SELECT destination_id FROM followee WHERE source_id = ?) ORDER BY created_at DESC LIMIT 20';
    $sth = $con->prepare($sql);
    $sth->execute(array(mt_rand(1, 10000)));
    $results = $sth->fetchAll();
    return $app['twig']->render('get_timeline.twig',['tweets' => $results]);
});
