<?php

require_once('Builder.php');
require_once('lime.php');

$output = new lime_output(true);
$test = new lime_test(null, array('force_colors' => true));

$qb = new QueryBuilder('master');

$sql = $qb->table('sample1')->getSql();
$test->is($sql, 'SELECT * FROM sample', 'get table');


$sql = $qb->table('sample2')
    ->select(array('id', 'name'))
    ->getSql();
$test->is($sql, 'SELECT id, name FROM sample', 'get table select');

