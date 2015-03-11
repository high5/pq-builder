<?php
namespace Test\Query;
/**
 * Class UnitTest
 */

class BuilderTest extends \PHPUnit_Framework_TestCase {


    public function testSql() {
        $qb = new \Query\Builder('master');
        $r = $qb->table('sample');
        $expected = 'SELECT * FROM sample';

        $this->assertEquals($r->getSql(),
            $expected,
            "not " . $expected
        );
        $this->assertEquals('works',
            'works',
            'This wil fail'
        );

    }

}