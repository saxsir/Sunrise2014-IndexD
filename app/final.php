<?php

$app->get('/',function() use($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/info',function() use($app) {
    return phpinfo();
});

$app->get('/get_timeline',function() use($app) {
    $con = $app['db'];
    $sql = 'SELECT id, body FROM tweets WHERE user_id IN (SELECT source_id FROM follows WHERE destination_id = ?) ORDER BY created_at DESC LIMIT 20)';
    $sth = $con->prepare($sql);
    $sth->execute(array(mt_rand(1, 100000)));
    $results = $sth->fetchAll();
    return $app['twig']->render('get_timeline.twig',['tweets' => $results]);
});
