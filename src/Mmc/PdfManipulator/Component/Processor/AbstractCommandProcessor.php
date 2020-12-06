<?php

namespace Mmc\PdfManipulator\Component\Processor;

use Mmc\PdfManipulator\Component\CommandWrapper\CommandTrait;

abstract class AbstractCommandProcessor extends AbstractProcessor
{
    use CommandTrait;

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
        $command = $this->getCommand($request);

        $this->logger->info(sprintf('Generate file "%s".', $request->getFilename()), [
            'command' => $command,
            'env' => $this->env,
            'timeout' => $this->timeout,
        ]);

        try {
            list($status, $stdout, $stderr) = $this->executeCommand($command);
            $this->checkProcessStatus($status, $stdout, $stderr, $command);
            $this->checkOutput($request->getFilename());
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('An error happened while generating "%s".', $request->getFilename()), [
                'command' => $command,
                'status' => isset($status) ? $status : null,
                'stdout' => isset($stdout) ? $stdout : null,
                'stderr' => isset($stderr) ? $stderr : null,
            ]);

            throw $e;
        }

        $this->logger->info(sprintf('File "%s" has been successfully generated.', $request->getFilename()), [
            'command' => $command,
            'stdout' => $stdout,
            'stderr' => $stderr,
        ]);

        return true;
    }
}
