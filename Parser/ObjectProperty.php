<?php

namespace pizzaminded\EntableBundle\Parser;

use pizzaminded\EntableBundle\Annotation\Header;
use ReflectionProperty;
use RuntimeException;

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

    public function getGetterFunctionName()
    {
        $functionName = $this->header->getGetter();

        if($functionName === null) {
            $functionName = 'get'.ucfirst($this->getPropertyName());
        }

        if(method_exists($this->reflection->class, $functionName)) {
            return $functionName;
        }

        throw new RuntimeException('Could not find a getter for "'.$this->getPropertyName().'" property');

    }
}