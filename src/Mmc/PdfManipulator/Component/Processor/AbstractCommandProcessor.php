<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\Exception;

abstract class AbstractCommandProcessor extends AbstractProcessor
{
    protected $binary;

    protected $env;

    protected $timeout = false;

    public function __construct(
        string $binary,
        array $env = null
    ) {
        parent::__construct();

        $this->setBinary($binary);
        $this->env = empty($env) ? null : $env;
    }

    abstract protected function getCommand($reference): string;

    protected function doProcess($request)
    {
        $command = $this->getCommand($reference);

        $this->logger->info(sprintf('Generate file "%s".', $reference->getFilename()), [
            'command' => $command,
            'env' => $this->env,
            'timeout' => $this->timeout,
        ]);

        try {
            list($status, $stdout, $stderr) = $this->executeCommand($command);
            $this->checkProcessStatus($status, $stdout, $stderr, $command);
            $this->checkOutput($output, $command);
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('An error happened while generating "%s".', $output), [
                'command' => $command,
                'status' => isset($status) ? $status : null,
                'stdout' => isset($stdout) ? $stdout : null,
                'stderr' => isset($stderr) ? $stderr : null,
            ]);

            throw $e;
        }

        $this->logger->info(sprintf('File "%s" has been successfully generated.', $output), [
            'command' => $command,
            'stdout' => $stdout,
            'stderr' => $stderr,
        ]);

        return true;
    }

    /**
     * Executes the given command via shell and returns the complete output as
     * a string.
     *
     * @param string $command
     *
     * @return array(status, stdout, stderr)
     */
    protected function executeCommand($command)
    {
        $process = new Process($command, null, $this->env);

        if (false !== $this->timeout) {
            $process->setTimeout($this->timeout);
        }

        $process->run();

        return [
            $process->getExitCode(),
            $process->getOutput(),
            $process->getErrorOutput(),
        ];
    }

    /**
     * Checks the process return status.
     *
     * @param int    $status  The exit status code
     * @param string $stdout  The stdout content
     * @param string $stderr  The stderr content
     * @param string $command The run command
     *
     * @throws \RuntimeException if the output file generation failed
     */
    protected function checkProcessStatus($status, $stdout, $stderr, $command): self
    {
        if (0 !== $status and '' !== $stderr) {
            throw new Exception\RuntimeException(sprintf('The exit status code \'%s\' says something went wrong:'."\n".'stderr: "%s"'."\n".'stdout: "%s"'."\n".'command: %s.', $status, $stderr, $stdout, $command));
        }

        return $this;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setBinary(string $binary): self
    {
        $this->binary = $binary;

        return $this;
    }
}
