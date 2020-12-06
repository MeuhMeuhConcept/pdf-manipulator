<?php

namespace Mmc\PdfManipulator\Component\PageCounter;

use Mmc\PdfManipulator\Component\Reference\Pdf;

class PageCounterMock implements PageCounterInterface
{
    protected $nbPages;

    public function __construct(
        $nbPages
    ) {
        $this->nbPages = $nbPages;
    }

    public function countPage(Pdf $reference): int
    {
        return $this->nbPages;
    }

    /**
     * @return mixed
     */
    public function getNbPages()
    {
        return $this->nbPages;
    }

    /**
     * @param mixed $nbPages
     *
     * @return self
     */
    public function setNbPages($nbPages)
    {
        $this->nbPages = $nbPages;

        return $this;
    }
}
