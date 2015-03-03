<?php

require_once('Builder.php');
require_once('lime.php');

$output = new lime_output(true);
$test = new lime_test(null, array('force_colors' => true));


$expected = 'SELECT * FROM sample';
$qb = new QueryBuilder('master');
$r = $qb->table('sample');
$test->is($r->getSql(), $expected, $expected);


$expected = 'SELECT id, name FROM sample';
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'));
$test->is($r->getSql(), $expected, $expected);


$expected = 'SELECT type, name FROM sample WHERE type = ?';
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('type', 'name'))
    ->where('type = ?', array(2));
$test->is($r->getSql(), $expected, $expected);
$test->is($r->getBindings(), array(2), 'bindings => array(2)');


$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY('id DESC');
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT id, name, count(*) as num FROM sample GROUP BY id, name";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name', 'count(*) as num'))
    ->groupBY('id, name');
$test->is($r->getSql(), $expected, $expected);

$expected = "SELECT id, name, count(*) as num FROM sample GROUP BY id, name";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name', 'count(*) as num'))
    ->groupBY(array('id', 'name'));
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC, name";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY(array('id DESC', 'name'));
$test->is($r->getSql(), $expected, $expected);











$test->diag('6');



