<?php

namespace Mmc\PdfManipulator\Component\FileManager;

interface FileManagerInterface
{
    public function createTemporaryFile(string $extension = null): string;

    public function prepareOutput(string $filename, bool $overwrite): void;

    public function getFileContents(string $filename): string;

    public function putFileContents(string $filename, string $content): int;

    public function fileExists(string $filename): bool;

    public function isFile(string $filename): bool;

    public function filesize(string $filename): int;

    //public function unlink(string $filename): bool;
    //public function isDir(string $filename): bool;
    //public function mkdir(string $pathname): bool;
    //public function isWritable(string $pathname): bool;
}
