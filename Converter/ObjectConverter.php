<?php

namespace pizzaminded\EntableBundle\Converter;


use Doctrine\Common\Annotations\AnnotationReader;

class ObjectConverter
{

    private $annotationReader;

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();

    }
}