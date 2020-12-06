<?php

namespace Mmc\PdfManipulator\Component;

class Helper
{
    protected $pageCounter;

    public function __construct(
        PageCounter\PageCounterInterface $pageCounter
    ) {
        $this->pageCounter = $pageCounter;
    }

    public function createFromFile(string $filename)
    {
        return new Reference\PdfFile($filename);
    }

    public function createFromRaw(string $content)
    {
        return new Reference\PdfRaw($content);
    }

    public function createMerger()
    {
        return new Reference\PdfMerge();
    }

    public function extractPages(Reference\Pdf $pdf, $pages)
    {
        if (!is_array($pages)) {
            $pages = [$pages];
        }

        $nbPages = $this->pageCounter->countPage($pdf);

        $pdfMerge = new Reference\PdfMerge();
        foreach ($pages as $page) {
            list($first, $last) = $this->extractPageRange($page, $nbPages);

            if ($first <= $last) {
                for ($i = $first; $i <= $last; ++$i) {
                    $pdfSeparate = new Reference\PdfExtractOne($pdf, $i);
                    $pdfMerge->addReference($pdfSeparate);
                }
            } else {
                for ($i = $first; $i >= $last; --$i) {
                    $pdfSeparate = new Reference\PdfExtractOne($pdf, $i);
                    $pdfMerge->addReference($pdfSeparate);
                }
            }
        }

        if (1 == count($pdfMerge->getReferences())) {
            return $pdfMerge->getReferences()[0];
        }

        return $pdfMerge;
    }

    public function extractPageRange($page, int $max)
    {
        $first = 1;
        $last = $max;

        if (is_string($page)) {
            if (preg_match('/^(end|\d*)(-(end|\d*))?$/', $page, $matches)) {
                $first = $matches[1] ? ('end' == $matches[1] ? $max : intval($matches[1])) : 1;
                if (isset($matches[2]) && isset($matches[3])) {
                    $last = 'end' == $matches[3] | '' == $matches[3] ? $max : intval($matches[3]);
                } else {
                    $last = $first;
                }
            }
        }
        if (is_int($page)) {
            $first = $page;
            $last = $page;
        }

        $first = min($first, $max);
        $last = min($last, $max);

        return [$first, $last];
    }

    public function countPages(Reference\Pdf $pdf)
    {
    }
}
