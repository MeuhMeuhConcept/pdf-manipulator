<?php

namespace Mmc\PdfManipulator\Component\FileManager;

class LocalFileManager implements FileManagerInterface
{
    /**
     * Wrapper for the "file_get_contents" function.
     *
     * @param string $filename
     */
    protected function getFileContents($filename): string
    {
        return file_get_contents($filename);
    }

    /**
     * Wrapper for the "file_put_contents" function.
     *
     * @param string $filename
     */
    protected function putFileContents($filename, $content): int
    {
        return file_put_contents($filename, $content);
    }

    /**
     * Wrapper for the "file_exists" function.
     *
     * @param string $filename
     */
    protected function fileExists($filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Wrapper for the "is_file" method.
     *
     * @param string $filename
     */
    protected function isFile($filename): bool
    {
        return strlen($filename) <= PHP_MAXPATHLEN && is_file($filename);
    }

    /**
     * Wrapper for the "filesize" function.
     *
     * @param string $filename
     *
     * @return int or FALSE on failure
     */
    protected function filesize($filename): int
    {
        return filesize($filename);
    }

    /**
     * Wrapper for the "unlink" function.
     *
     * @param string $filename
     */
    protected function unlink($filename): bool
    {
        return $this->fileExists($filename) ? unlink($filename) : false;
    }

    /**
     * Wrapper for the "is_dir" function.
     *
     * @param string $filename
     */
    protected function isDir($filename): bool
    {
        return is_dir($filename);
    }

    /**
     * Wrapper for the mkdir function.
     *
     * @param string $pathname
     */
    protected function mkdir($pathname): bool
    {
        return mkdir($pathname, 0777, true);
    }

    /**
     * Wrapper for the "is_writable" function.
     *
     * @param string $filename
     */
    protected function isWritable($filename): bool
    {
        return is_writable($filename);
    }
}
