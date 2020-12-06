<?php

namespace Mmc\PdfManipulator\Component;

use Mmc\PdfManipulator\Component\PageCounter\PageCounterMock;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    protected $helper;
    protected $pageCounter;

    public function setup(): void
    {
        $this->pageCounter = new PageCounterMock([]);
        $this->helper = new Helper($this->pageCounter);
    }

    public function testExtractPageRangeSimple()
    {
        $res = $this->helper->extractPageRange(1, 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 1);
        $this->assertEquals($res[1], 1);
    }

    public function testExtractPageRangeLimit()
    {
        $res = $this->helper->extractPageRange(25, 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 10);
        $this->assertEquals($res[1], 10);
    }

    public function testExtractPageRangeString()
    {
        $res = $this->helper->extractPageRange('1', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 1);
        $this->assertEquals($res[1], 1);
    }

    public function testExtractPageRangeComplete()
    {
        $res = $this->helper->extractPageRange('5-8', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 5);
        $this->assertEquals($res[1], 8);
    }

    public function testExtractPageRangeReverse()
    {
        $res = $this->helper->extractPageRange('5-2', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 5);
        $this->assertEquals($res[1], 2);
    }

    public function testExtractPageRangeInverse()
    {
        $res = $this->helper->extractPageRange('end-3', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 10);
        $this->assertEquals($res[1], 3);
    }

    public function testExtractPageRangeUntil()
    {
        $res = $this->helper->extractPageRange('-3', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 1);
        $this->assertEquals($res[1], 3);
    }

    public function testExtractPageRangeFrom()
    {
        $res = $this->helper->extractPageRange('3-', 10);

        $this->assertCount(2, $res);
        $this->assertEquals($res[0], 3);
        $this->assertEquals($res[1], 10);
    }
}
