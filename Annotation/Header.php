<?php

namespace pizzaminded\EntableBundle\Annotation;

/**
 * Class Row
 * @package pizzaminded\EntableBundle\Annotation
 * @author pizzaminded <github.com/pizzaminded>
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Header
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $getter;


    /**
     * @var string
     */
    private $order;

    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->{$key} = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Oh, what a fate's irony!
     * @return string|null
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }



}