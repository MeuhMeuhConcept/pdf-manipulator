<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\FileManager\FileManagerInterface;
use Mmc\Processor\Component\Processor;
use Mmc\Processor\Component\ProcessorTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractProcessor implements Processor
{
    use ProcessorTrait;

    protected $fileManager;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * Set the fileManager to check output.
     */
    public function setFileManagerInterface(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Set the logger to use to log debugging data.
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Checks the specified output.
     *
     * @param string $output The output filename
     *
     * @throws \RuntimeException if the output file generation failed
     */
    protected function checkOutput($output): self
    {
        if (!$this->fileManager) {
            return $this;
        }

        // the output file must exist
        if (!$this->fileManager->fileExists($output)) {
            throw new Exception\RuntimeException(sprintf('The file \'%s\' was not created.', $output));
        }

        // the output file must not be empty
        if (0 === $this->fileManager->filesize($output)) {
            throw new Exception\RuntimeException(sprintf('The file \'%s\' was created but is empty.', $output));
        }

        return $this;
    }
}
