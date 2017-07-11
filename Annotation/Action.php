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
    private $propertyName;

    private $dataType = 'string';

    private $name;

    private $route;


    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {

            if (!property_exists($this, $key)) {
                throw new InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->{$key} = $value;
        }
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

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


}