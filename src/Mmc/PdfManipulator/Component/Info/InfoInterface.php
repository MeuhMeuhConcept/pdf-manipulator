<?php

namespace Mmc\PdfManipulator\Component\Info;

interface InfoInterface
{
    public function get(string $filename): array;
}
