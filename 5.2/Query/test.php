<?php

require_once('Builder.php');
require_once('lime.php');

$output = new lime_output(true);
$test = new lime_test(null, array('force_colors' => true));


$test->diag('1');
$qb = new QueryBuilder('master');
$r = $qb->table('sample');
$expected = 'SELECT * FROM sample';
$test->is($r->getSql(), $expected, $expected);


$test->diag('2');
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'));
$expected = 'SELECT id, name FROM sample';
$test->is($r->getSql(), $expected, $expected);

$test->diag('3');
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('type', 'name'))
    ->where('type = ?', array(2));
$expected = "SELECT type, name FROM sample WHERE type = ?";
$test->is($r->getSql(), $expected, $expected);
$test->is($r->getBindings(), array(2), 'bindings => array(2)');

$test->diag('4');
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY('id DESC');
$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC";
$test->is($r->getSql(), $expected, $expected);

$test->diag('5');
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name', 'count(*) as num'))
    ->groupBY('id, name');
$expected = "SELECT id, name, count(*) as num FROM sample GROUP BY id, name";
$test->is($r->getSql(), $expected, $expected);



