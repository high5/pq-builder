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


$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC, name LIMIT ?, ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY(array('id DESC', 'name'))
    ->limit(5, 10);
$test->is($r->getBindings(), array(2, 5, 10), 'array(2, 5, 10)');
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC, name LIMIT ?, ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY(array('id DESC', 'name'))
    ->offset(5)
    ->limit(10);
$test->is($r->getBindings(), array(2, 5, 10), 'array(2, 5, 10)');
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC, name LIMIT ?, ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample');
$r->limit(10);
$r->offset(5);
$r->orderBY(array('id DESC', 'name'));
$r->where('id = ?', array(2));
$r->select(array('id', 'name'));
$test->is($r->getBindings(), array(2, 5, 10), 'array(2, 5, 10)');
$test->is($r->getSql(), $expected, $expected);



$expected = "SELECT id, name FROM sample WHERE id = ? ORDER BY id DESC, name LIMIT ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->select(array('id', 'name'))
    ->where('id = ?', array(2))
    ->orderBY(array('id DESC', 'name'))
    ->limit(10);
$test->is($r->getBindings(), array(2, 10), 'array(2, 10)');
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT s1.sample_id as id, s2.name FROM sample1 s1 LEFT JOIN sample2 s2 ON s1.sample_id = s2.sample_id WHERE id = ? AND s1.name = ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample1 s1')
    ->select(array('s1.sample_id as id', 's2.name'))
    ->leftJoin('sample2 s2', 's1.sample_id = s2.sample_id')
    ->where('id = ? AND s1.name = ?', array(2, 'KEN'));
$test->is($r->getBindings(), array(2, 'KEN'), "array(2, 'KEN')");
$test->is($r->getSql(), $expected, $expected);


$expected = "SELECT * FROM sample LIMIT ?";
$qb = new QueryBuilder('master');
$r = $qb->table('sample')
    ->limit(10);
$test->is($r->getBindings(), array(10), "array(10)");
$test->is($r->getSql(), $expected, $expected);



