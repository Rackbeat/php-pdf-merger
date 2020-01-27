<p align="center" style="text-align: center">
<img src="logo.png" alt="Rackbeat PDF merger" height="100" />
<br/>Merge PDFs into one PDF. Supports files, raw PDFs and Dompdf (+ Barryvdh Dompdf)
</p>
    
<p align="center" style="text-align: center"> 
<a href="https://travis-ci.org/Rackbeat/php-pdf-merger"><img src="https://img.shields.io/travis/Rackbeat/php-pdf-merger.svg?style=flat-square" alt="Build Status"></a>
<a href="https://coveralls.io/github/Rackbeat/php-pdf-merger"><img src="https://img.shields.io/coveralls/Rackbeat/php-pdf-merger.svg?style=flat-square" alt="Coverage"></a>
<a href="https://packagist.org/packages/rackbeat/php-pdf-merger"><img src="https://img.shields.io/packagist/dt/rackbeat/php-pdf-merger.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/rackbeat/php-pdf-merger"><img src="https://img.shields.io/packagist/v/rackbeat/php-pdf-merger.svg?style=flat-square" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/rackbeat/php-pdf-merger"><img src="https://img.shields.io/packagist/l/rackbeat/php-pdf-merger.svg?style=flat-square" alt="License"></a>
</p>

## Installation

You just require using composer and you're good to go! 

```bash
composer require rackbeat/php-pdf-merger
```

## Usage

### Basic example

```php
use Rackbeat\PDFMerger\MergeablePDF;

$pdf = MergeablePDF::make('path/to/my/file.pdf')
                   ->add('RAW_CONTENT_FROM_ANOTHER_PDF')
                   // Add multiple PDFs from array
                   ->add(['path/to/other.pdf', 'RAW_PDF_CONTENT']);
                    
// Merge directly
echo $pdf->merge();

// Use __toString()
echo $pdf;
```

### Save PDF

```php
use Rackbeat\PDFMerger\MergeablePDF;

MergeablePDF::make('path/to/my/file.pdf')
            ->add('RAW_CONTENT_FROM_ANOTHER_PDF');
            ->save('path/to/new/file.pdf');
```

### Output PDF response (Laravel)

```php
use Rackbeat\PDFMerger\MergeablePDF;

return MergeablePDF::make('path/to/my/file.pdf')
            ->add('RAW_CONTENT_FROM_ANOTHER_PDF');
            ->response('filename.pdf');
```

### Download PDF response (Laravel)

```php
use Rackbeat\PDFMerger\MergeablePDF;

return MergeablePDF::make('path/to/my/file.pdf')
            ->add('RAW_CONTENT_FROM_ANOTHER_PDF');
            ->download('filename.pdf');
```

## Requirements
* PHP >= 7.3
