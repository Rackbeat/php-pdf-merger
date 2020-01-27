<?php namespace Rackbeat\PDFMerger\Tests;

use PHPUnit\Framework\TestCase;
use Rackbeat\PDFMerger\Merger;

class PDFMergerTest extends TestCase
{
	/** @var Merger */
	protected $throttler;

	public function setUp() {
		parent::setUp();

		$this->throttler = new Merger( [] );
	}

	/** @test */
	public function it_can_count_iterations_to_do() {
		$this->throttler->setIterable( [ 1, 2, 3 ] );

		$this->assertEquals( 3, $this->throttler->getIterator()->count() );

		$this->throttler->setIterable( [] );

		$this->assertEquals( 0, $this->throttler->getIterator()->count() );

		$this->throttler->setIterable( range( 1, 100 ) );

		$this->assertEquals( 100, $this->throttler->getIterator()->count() );
	}
}
