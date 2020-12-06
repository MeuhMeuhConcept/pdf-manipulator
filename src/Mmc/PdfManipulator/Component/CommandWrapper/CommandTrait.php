<?php

namespace Mmc\PdfManipulator\Component\CommandWrapper;

use Mmc\PdfManipulator\Component\Exception;
use Symfony\Component\Process\Process;

trait CommandTrait
{
    protected $binary;

    protected $env;

    protected $timeout = false;

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

        if (0 < $this->timeout) {
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
