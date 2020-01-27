<?php namespace Rackbeat\PDFMerger\Tests;

use PHPUnit\Framework\TestCase;
use Rackbeat\PDFMerger\MergeablePDF;
use Rackbeat\PDFMerger\Merger;

class MergeablePDFtest extends TestCase
{
	/** @test */
	public function it_can_count_iterations_to_do() {
		MergeablePDF::make( './stubs/random.pdf' )
		            ->add( './stubs/random.pdf' )
		            ->merge();
	}
}
