<?php

namespace pizzaminded\EntableBundle\Parser;

use DateTime;
use Doctrine\Common\Annotations\AnnotationReader;
use pizzaminded\EntableBundle\Annotation\Action;
use pizzaminded\EntableBundle\Annotation\ActionField;
use pizzaminded\EntableBundle\Annotation\Header;
use pizzaminded\EntableBundle\Builder\TableBuilder;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Routing\Router;
use Twig_Environment;

/**
 * Class ObjectParser
 * @package pizzaminded\EntableBundle\Parser
 * @author pizzaminded <github.com/pizzaminded>
 */
class ObjectParser
{

    /**
     * @var string FQCN of parsed entity
     */
    private $className;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var array
     */
    private $collection;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var TableBuilder
     */
    private $tableBuilder;

    /**
     * ObjectParser constructor.
     * @param $twig
     * @param $translator
     * @param $router
     */
    public function __construct($twig, $translator, $router)
    {

        $this->twig = $twig;
        $this->translator = $translator;
        $this->router = $router;
        $this->annotationReader = new AnnotationReader();
        $this->tableBuilder = new TableBuilder();
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @param mixed $collection
     * @return ObjectParser
     */
    public function setCollection($collection): ObjectParser
    {
        $this->collection = $collection;
        return $this;
    }


    /**
     * @return string
     * @throws \Symfony\Component\Translation\Exception\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    public function renderTable(): string
    {
        if ($this->getClassName() === null) {
            throw new RuntimeException('No class set.');
        }

        $reflectionClass = new ReflectionClass($this->getClassName());

        /** @var ReflectionProperty[] $objectProperties */
        $objectProperties = $reflectionClass->getProperties();

        $headers = [];
        /** @var ObjectProperty[] $objectRenderPriority */
        $objectRenderPriority = [];
        //get table header
        foreach ($objectProperties as $objectProperty) {
            if ($this->shouldPropertyBeParsed($objectProperty)) {
                //set column header
                $header = $this->getHeaderAnnotation($objectProperty);
                $objectPropertyClass = new ObjectProperty($header, $objectProperty);

                if (!is_numeric($header->getOrder()) || $header->getOrder() === null) {
                    throw new InvalidArgumentException(
                        'Property "' . $objectProperty->getName() . '" is not set or has not no numeric value for order param.');
                }

                $headers[(int)$header->getOrder()] = $header;
                $objectRenderPriority[(int)$header->getOrder()] = $objectPropertyClass;

            }
        }

        //sort arrays to make thing ordered
        ksort($headers);
        ksort($objectRenderPriority);

        $actionField = $this->annotationReader->getClassAnnotation($reflectionClass,ActionField::class);
        if($actionField !== null) {
            $headers[] = $actionField;
        }

        $this->setHeaders($headers);

        #TODO: refactor
        foreach ($this->collection as $item) {
            $row = [];
            foreach ($objectRenderPriority as $object) {
                if ($object->isPublic()) {
                    $propertyName = $object->getPropertyName();
                    $value = $item->{$propertyName};
                } else {
                    $methodName = $object->getGetterFunctionName();
                    $value = $item->$methodName();
                }

                if($value instanceof DateTime) {
                    /** @var DateTime $value */
                    $value = $value->format('d-m-Y H:i:s');
                }

                $row[] = htmlspecialchars($value);
            }

            //if there is an ActionField annotation defined, parse action links
            if($actionField !== null) {
                $action = '';
                $reflections = $this->annotationReader->getClassAnnotations($reflectionClass);
                foreach ($reflections as $reflection) {
                    /** @var Action $reflection */
                    if($reflection instanceof Action) {
                        $url = $this->router->generate($reflection->getRoute(), ['id' => $item->getId()]);
                        $name = $this->translator->trans($reflection->getName());
                        $targetString = null;

                        if($reflection->getTarget()) {
                            $targetString = 'target="'.$reflection->getTarget().'"';
                        }
                        $action .= '<a class="entable__action_link" 
                        '.$targetString.'
                        href="'.$url.'">'.$name.'</a>';
                    }

                }
                $row[] = $action;
            }

            $this->addRow($row);
        }
        return $this->twig->render('@pizzamindedEntable/table.html.twig', ['table' => $this->tableBuilder]);

    }

    /**
     * @param ReflectionProperty $property
     * @return bool
     */
    private function shouldPropertyBeParsed(ReflectionProperty $property): bool
    {
        return $this->getHeaderAnnotation($property) !== null;

    }

    /**
     * @param ReflectionProperty $property
     * @return Header|null
     */
    private function getHeaderAnnotation(ReflectionProperty $property)
    {
        return $this->annotationReader->getPropertyAnnotation($property, Header::class);
    }

    /**
     * @param ReflectionProperty $property
     * @return array
     */
    private function getPropertyAnnotations(ReflectionProperty $property)
    {
        return $this->annotationReader->getPropertyAnnotations($property);
    }

    private function setHeaders(array $header)
    {
        $this->tableBuilder->setHeaders($header);

        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    private function addRow(array $row)
    {
        $this->tableBuilder->addRow($row);

        return $this;
    }
}