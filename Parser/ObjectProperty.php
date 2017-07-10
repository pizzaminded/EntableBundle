<?php

namespace pizzaminded\EntableBundle\Parser;

use pizzaminded\EntableBundle\Annotation\Header;
use ReflectionProperty;

/**
 * Class ObjectProperty
 * @package pizzaminded\EntableBundle\Parser
 */
class ObjectProperty
{
    /**
     * @var Header
     */
    protected $header;

    /**
     * @var ReflectionProperty
     */
    protected $reflection;

    /**
     * ObjectProperty constructor.
     * @param Header $header
     * @param ReflectionProperty $reflection
     */
    public function __construct(Header $header, ReflectionProperty $reflection)
    {
        $this->header = $header;
        $this->reflection = $reflection;
    }


    public function isPublic(): bool
    {
        return $this->reflection->getModifiers() < $this->reflection::IS_PROTECTED;
    }

    public function getPropertyName(): string
    {
        return $this->reflection->getName();
    }
}