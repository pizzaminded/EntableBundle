<?php

namespace pizzaminded\EntableBundle\Annotation;

/**
 * Class Row
 * @package pizzaminded\EntableBundle\Annotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Header
{
    private $propertyName;
    private $dataType = 'string';

    private $title;

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



    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getDataType()
    {
        return $this->dataType;
    }
}