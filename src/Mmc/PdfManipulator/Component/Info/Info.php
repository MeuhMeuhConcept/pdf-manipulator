<?php

namespace Mmc\PdfManipulator\Component\Info;

use Mmc\PdfManipulator\Component\CommandWrapper\CommandTrait;

class Info implements InfoInterface
{
    use CommandTrait;

    public function __construct(
        string $binary,
        array $env = null
    ) {
        $this->setBinary($binary);
        $this->env = empty($env) ? null : $env;
    }

    public function get(string $filename): array
    {
        $command = sprintf(
            '%s %s',
            $this->binary,
            $filename
        );

        list($status, $stdout, $stderr) = $this->executeCommand($command);
        $this->checkProcessStatus($status, $stdout, $stderr, $command);

        $infos = [];

        foreach (explode("\n", $stdout) as $line) {
            try {
                list($k, $v) = explode(':', $line);

                $v = trim($v);

                if (is_numeric($v)) {
                    $v = floatval($v) == intval($v) ? intval($v) : floatval($v);
                }

                $infos[trim($k)] = $v;
            } catch (\Throwable $e) {
                //ignore line
            }
        }

        return $infos;
    }
}
