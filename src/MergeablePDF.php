<?php namespace Rackbeat\PDFMerger;

use iio\libmergepdf\PagesInterface;
use iio\libmergepdf\Merger as LibMerger;
use Rackbeat\PDFMerger\Exceptions\LaravelIsNotInstalledException;
use Rackbeat\PDFMerger\Exceptions\PageIsEmptyException;

class MergeablePDF
{
	/** @var LibMerger */
	protected $merger;

	/**
	 * @param string|\SplFileObject|\Barryvdh\DomPDF\PDF|\Dompdf\Dompdf $source
	 * @param null|PagesInterface                                       $pagesConstraint
	 *
	 * @throws PageIsEmptyException
	 */
	public function __construct( $source = null, ?PagesInterface $pagesConstraint = null ) {
		$this->merger = new LibMerger();

		if ( $source ) {
			$this->add( $source, $pagesConstraint );
		}
	}

	/**
	 * @param string|\SplFileObject|\Barryvdh\DomPDF\PDF|\Dompdf\Dompdf $source
	 * @param null|PagesInterface                                       $pagesConstraint
	 *
	 * @return self
	 * @throws PageIsEmptyException
	 */
	public static function make( $source, ?PagesInterface $pagesConstraint = null ): self {
		return new self( $source, $pagesConstraint );
	}

	public function getMerger(): LibMerger {
		return $this->merger;
	}

	/**
	 * @param string|\SplFileObject|\Barryvdh\DomPDF\PDF|\Dompdf\Dompdf|array|string[]|\SplFileObject[]|\Barryvdh\DomPDF\PDF[]|\Dompdf\Dompdf[] $page
	 * @param null|PagesInterface                                                                                                               $pagesConstraint
	 *
	 * @return self
	 * @throws PageIsEmptyException
	 */
	public function add( $page, ?PagesInterface $pagesConstraint = null ): self {
		if ( ! $page ) {
			throw new PageIsEmptyException( 'The passed in page is empty' );
		}

		// Helper to allow array inside the add method.
		if ( is_array( $page ) ) {
			return $this->addMany( $page, $pagesConstraint );
		}

		if ( $page instanceof \SplFileObject ) {
			$this->merger->addRaw( $page->fread( $page->getSize() ) );
		} elseif ( $page instanceof \Barryvdh\DomPDF\PDF || $page instanceof \Dompdf\Dompdf ) {
			$this->merger->addRaw( $page->output() );
		} elseif ( file_exists( $page ) ) {
			$this->merger->addFile( $page, $pagesConstraint );
		} else {
			$this->merger->addRaw( $page, $pagesConstraint );
		}

		return $this;
	}

	/**
	 * @param array|string[]|\SplFileObject[]|\Barryvdh\DomPDF\PDF[]|\Dompdf\Dompdf[] $pages
	 * @param null|PagesInterface                                                     $pagesConstraint
	 *
	 * @return self
	 * @throws PageIsEmptyException
	 */
	public function addMany( array $pages = [], ?PagesInterface $pagesConstraint = null ): self {
		foreach ( $pages as $page ) {
			$this->addPage( $page, $pagesConstraint );
		}

		return $this;
	}

	public function reset(): self {
		$this->merger->reset();

		return $this;
	}

	/**
	 * Save to local file.
	 *
	 * @param string $filename
	 *
	 * @return bool|int
	 */
	public function save( $filename = 'file.pdf' ) {
		return file_put_contents( $filename, $this->merge() );
	}

	/**
	 * Laravel helper to send stream response
	 *
	 * @param string $filename
	 * @param int    $status
	 *
	 * @return \Illuminate\Http\Response
	 * @throws LaravelIsNotInstalledException
	 */
	public function response( $filename = 'file.pdf', $status = 200 ) {
		if ( ! class_exists( \Illuminate\Http\Response::class ) ) {
			throw new LaravelIsNotInstalledException( 'Could not find Laravel \Illuminate\Http\Response class.' );
		}

		return new \Illuminate\Http\Response(
			$this->merge(), $status, [
				'Content-Type'        => 'application/pdf',
				'Content-Disposition' => 'inline; filename="' . $filename . '"',
			]
		);
	}

	/**
	 * Laravel helper to send download response
	 *
	 * @param string $filename
	 * @param int    $status
	 *
	 * @return \Illuminate\Http\Response
	 * @throws LaravelIsNotInstalledException
	 */
	public function download( $filename = 'file.pdf', $status = 200 ) {
		if ( ! class_exists( \Illuminate\Http\Response::class ) ) {
			throw new LaravelIsNotInstalledException( 'Could not find Laravel \Illuminate\Http\Response class.' );
		}

		return new \Illuminate\Http\Response(
			$this->merge(), $status, [
				'Content-Type'        => 'application/pdf',
				'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			]
		);
	}

	public function merge(): string {
		return $this->merger->merge();
	}

	public function __toString() {
		return $this->merge();
	}
}