<?php

namespace pizzaminded\EntableBundle\Annotation;
use InvalidArgumentException;

/**
 * Class ActionField
 * @package pizzaminded\EntableBundle\Annotation
 * @Annotation
 * @Target({"CLASS"})
 */
class ActionField
{


    private $dataType = 'string';

    /**
     * @var string
     */
    private $title;




    public function __construct(array $options)
    {

        foreach ($options as $key => $value) {

            if (!property_exists($this, $key)) {
                throw new InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->{$key} = $value;
        }
    }


    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }




}