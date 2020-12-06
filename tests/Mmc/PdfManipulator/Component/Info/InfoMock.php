<?php

namespace Mmc\PdfManipulator\Component\Info;

class InfoMock implements InfoInterface
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function get(string $filename): array
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     *
     * @return self
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }
}
