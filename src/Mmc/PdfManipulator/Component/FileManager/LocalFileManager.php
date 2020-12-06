<?php

namespace Mmc\PdfManipulator\Component\FileManager;

class LocalFileManager implements FileManagerInterface
{
    /**
     * @var string
     */
    protected $prefix = 'mmc_pdf_manipulator_';

    /**
     * @var string
     */
    protected $temporaryFolder;

    /**
     * @var array
     */
    protected $temporaryFiles = [];

    public function __construct()
    {
        register_shutdown_function([$this, 'removeTemporaryFiles']);
    }

    public function __destruct()
    {
        $this->removeTemporaryFiles();
    }

    /**
     * Creates a temporary file.
     * The file is not created if the $content argument is null.
     *
     * @param string $extension An optional extension for the filename
     *
     * @return string The filename
     */
    public function createTemporaryFile(string $extension = null): string
    {
        $dir = rtrim($this->getTemporaryFolder(), DIRECTORY_SEPARATOR);

        if (!$this->isDir($dir)) {
            if (false === $this->mkdir($dir) && !$this->isDir($dir)) {
                throw new Exception\RuntimeException(sprintf("Unable to create directory: %s\n", $dir));
            }
        } elseif (!$this->isWritable($dir)) {
            throw new Exception\RuntimeException(sprintf("Unable to write in directory: %s\n", $dir));
        }

        $filename = $dir.DIRECTORY_SEPARATOR.uniqid($this->prefix, true);

        if (null !== $extension) {
            $filename .= '.'.$extension;
        }

        $this->temporaryFiles[] = $filename;

        return $filename;
    }

    /**
     * Prepares the specified output.
     *
     * @param string $filename  The output filename
     * @param bool   $overwrite Whether to overwrite the file if it already
     *                          exist
     *
     * @throws Exception\FileAlreadyExistsException
     * @throws Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    public function prepareOutput(string $filename, bool $overwrite): void
    {
        $directory = dirname($filename);

        if ($this->fileExists($filename)) {
            if (!$this->isFile($filename)) {
                throw new Exception\InvalidArgumentException(sprintf('The output file \'%s\' already exists and it is a %s.', $filename, $this->isDir($filename) ? 'directory' : 'link'));
            } elseif (false === $overwrite) {
                throw new Exception\FileAlreadyExistsException(sprintf('The output file \'%s\' already exists.', $filename));
            } elseif (!$this->unlink($filename)) {
                throw new Exception\RuntimeException(sprintf('Could not delete already existing output file \'%s\'.', $filename));
            }
        } elseif (!$this->isDir($directory) && !$this->mkdir($directory)) {
            throw new Exception\RuntimeException(sprintf('The output file\'s directory \'%s\' could not be created.', $directory));
        }
    }

    /**
     * Get Prefix.
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Set Prefix.
     *
     * @return self
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get TemporaryFolder.
     */
    public function getTemporaryFolder(): string
    {
        if (null === $this->temporaryFolder) {
            return sys_get_temp_dir();
        }

        return $this->temporaryFolder;
    }

    /**
     * Set temporaryFolder.
     *
     * @param string $temporaryFolder
     *
     * @return $this
     */
    public function setTemporaryFolder($temporaryFolder)
    {
        $this->temporaryFolder = $temporaryFolder;

        return $this;
    }

    /**
     * Removes all temporary files.
     */
    public function removeTemporaryFiles(): self
    {
        foreach ($this->temporaryFiles as $file) {
            $this->unlink($file);
        }

        $this->temporaryFiles = [];

        return $this;
    }

    /**
     * Wrapper for the "file_get_contents" function.
     */
    public function getFileContents(string $filename): string
    {
        return file_get_contents($filename);
    }

    /**
     * Wrapper for the "file_put_contents" function.
     */
    public function putFileContents(string $filename, string $content): int
    {
        return file_put_contents($filename, $content);
    }

    /**
     * Wrapper for the "file_exists" function.
     */
    public function fileExists(string $filename): bool
    {
        return file_exists($filename);
    }

    /**
     * Wrapper for the "is_file" method.
     */
    public function isFile(string $filename): bool
    {
        return strlen($filename) <= PHP_MAXPATHLEN && is_file($filename);
    }

    /**
     * Wrapper for the "filesize" function.
     *
     * @return int or FALSE on failure
     */
    public function filesize(string $filename): int
    {
        return filesize($filename);
    }

    /**
     * Wrapper for the "unlink" function.
     */
    public function unlink(string $filename): bool
    {
        return $this->fileExists($filename) ? unlink($filename) : false;
    }

    /**
     * Wrapper for the "is_dir" function.
     */
    public function isDir(string $filename): bool
    {
        return is_dir($filename);
    }

    /**
     * Wrapper for the mkdir function.
     */
    public function mkdir(string $pathname): bool
    {
        return mkdir($pathname, 0777, true);
    }

    /**
     * Wrapper for the "is_writable" function.
     */
    public function isWritable(string $filename): bool
    {
        return is_writable($filename);
    }
}
