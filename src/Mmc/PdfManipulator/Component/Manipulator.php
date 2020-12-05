<?php

namespace Mmc\PdfManipulator\Component;

use Mmc\PdfManipulator\Component\FileManager\FileManagerInterface;
use Mmc\Processor\Component\ChainProcessor;
use Mmc\Processor\Component\ResponseStatusCode;

class Manipulator extends ChainProcessor
{
    /**
     * @var string
     */
    protected $prefix = 'mmc_pdf_manipulator';

    /**
     * @var string
     */
    protected $temporaryFolder;

    /**
     * @var array
     */
    public $temporaryFiles = [];

    /**
     * @var FileManagerInterface
     */
    public $fileManager;

    public function __construct(
        FileManagerInterface $fileManager
    ) {
        $this->fileManager = $fileManager;

        register_shutdown_function([$this, 'removeTemporaryFiles']);
    }

    public function __destruct()
    {
        $this->removeTemporaryFiles();
    }

    public function generate(Reference\Pdf $reference, $overwrite = false): self
    {
        $filename = $reference->getFilename();

        if (!$filename) {
            $filename = $this->createTemporaryFile('pdf');
            $reference->setFilename($filename);
        }

        $this->prepareOutput($filename, $overwrite);

        $response = $this->process($reference);

        if (ResponseStatusCode::INTERNAL_ERROR == $response->getStatusCode()) {
            throw new Exception\RuntimeException($response->getOutput());
        }
    }

    public function getContent(Reference\Pdf $reference): string
    {
        $filename = $reference->getFilename();

        if (!$filename || !$this->fileExists($filename) || !$this->isFile($filename)) {
            $this->generate($reference);
        }

        $result = $this->fileManager->getFileContents($reference->getFilename());

        return $result;
    }

    /**
     * Creates a temporary file.
     * The file is not created if the $content argument is null.
     *
     * @param string $extension An optional extension for the filename
     *
     * @return string The filename
     */
    protected function createTemporaryFile($extension = null)
    {
        $dir = rtrim($this->getTemporaryFolder(), DIRECTORY_SEPARATOR);

        if (!$this->fileManager->isDir($dir)) {
            if (false === $this->fileManager->mkdir($dir) && !$this->fileManager->isDir($dir)) {
                throw new Exception\RuntimeException(sprintf("Unable to create directory: %s\n", $dir));
            }
        } elseif (!$this->fileManager->isWritable($dir)) {
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
    protected function prepareOutput($filename, $overwrite)
    {
        $directory = dirname($filename);

        if ($this->fileManager->fileExists($filename)) {
            if (!$this->fileManager->isFile($filename)) {
                throw new Exception\InvalidArgumentException(sprintf('The output file \'%s\' already exists and it is a %s.', $filename, $this->fileManager->isDir($filename) ? 'directory' : 'link'));
            } elseif (false === $overwrite) {
                throw new Exception\FileAlreadyExistsException(sprintf('The output file \'%s\' already exists.', $filename));
            } elseif (!$this->fileManager->unlink($filename)) {
                throw new Exception\RuntimeException(sprintf('Could not delete already existing output file \'%s\'.', $filename));
            }
        } elseif (!$this->fileManager->isDir($directory) && !$this->fileManager->mkdir($directory)) {
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
            $this->fileManager->unlink($file);
        }

        return $this;
    }
}
