<?php

namespace pizzaminded\EntableBundle\Annotation;

/**
 * Class Row
 * @package pizzaminded\EntableBundle\Annotation
 * @Annotation
 * @Target({"CLASS"})
 */
class Row
{
    private $propertyName;
    private $dataType = 'string';

    private $class;

    public function __construct($options)
    {
        if (isset($options['value'])) {
            $options['propertyName'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }



    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getDataType()
    {
        return $this->dataType;
    }
}