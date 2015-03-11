<?php
namespace Test\Query;
/**
 * Class UnitTest
 */

class BuilderTest extends \PHPUnit_Framework_TestCase {

    public function testTestCase() {

        $this->assertEquals('works',
            'works',
            'This is OK'
        );
        $this->assertEquals('works',
            'works',
            'This wil fail'
        );


    }

}