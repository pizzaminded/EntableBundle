<?php

namespace pizzaminded\EntableBundle\Annotation;
use InvalidArgumentException;

/**
 * Class Action
 * @package pizzaminded\EntableBundle\Annotation
 * @Annotation
 * @Target({"CLASS"})
 */
class Action
{

    /**
     * @var string
     */
    private $dataType = 'string';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string|null
     */
    private $target = null;


    /**
     * Action constructor.
     * @param $options
     * @throws \InvalidArgumentException
     */
    public function __construct($options)
    {
        foreach ($options as $key => $value) {

            if (!property_exists($this, $key)) {
                throw new InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->{$key} = $value;
        }
    }


    /**
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return null|string
     */
    public function getTarget()
    {
        return $this->target;
    }

}