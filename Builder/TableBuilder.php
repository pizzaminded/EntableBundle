<?php

namespace pizzaminded\EntableBundle\Builder;


use pizzaminded\EntableBundle\Annotation\Header;

class TableBuilder
{
    private $headers = [];

    private $content = [];

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @param Header $header
     * @return $this
     */
    public function addHeader($name, Header $header)
    {
        $this->headers[$name] = $header->getTitle();

        return $this;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }


    public function addRow(array $row)
    {
        $this->content[] = $row;

        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }




}