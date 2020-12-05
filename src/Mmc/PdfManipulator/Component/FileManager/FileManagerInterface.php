<?php

namespace Mmc\PdfManipulator\Component\FileManager;

interface FileManagerInterface
{
    protected function getFileContents($filename): string;
    protected function putFileContents($filename, $content): int;
    protected function fileExists($filename): bool;
    protected function isFile($filename): bool;
    protected function filesize($filename): int;
    protected function unlink($filename): bool;
    protected function isDir($filename): bool;
    protected function mkdir($pathname): bool;
    protected function isWritable($pathname): bool;
}
