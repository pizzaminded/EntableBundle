<?php

namespace pizzaminded\EntableBundle\Twig\Extension;

use pizzaminded\EntableBundle\Parser\ObjectParser;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;
use Twig_Environment;
use Twig_Extension;

/**
 * Class EntableExtension
 * @package pizzaminded\EntableBundle\Twig\Extension
 * @author pizzaminded <github.com/pizzaminded>
 */
class EntableExtension extends Twig_Extension
{

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
     * @var ObjectParser
     */
    private $objectParser;

    /**
     * @var bool
     */
    private $isObjectDetected = false;


    public function __construct($twig, $translator, $router)
    {

        $this->twig = $twig;
        $this->translator = $translator;
        $this->router = $router;

        $this->objectParser = new ObjectParser($twig, $translator, $router);

    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('entable', [$this, 'entable'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function entable($objectCollection, $renderActionCells = true)
    {
        if (count($objectCollection) === 0) {
            return;
        }

        foreach ($objectCollection as $object) {

            if (!$this->isObjectDetected) {
                $this->objectParser->setClassName(get_class($object));
                $this->isObjectDetected = true;
            }
        }
        $this->objectParser->setCollection($objectCollection);
        $this->objectParser->setRenderActionCells($renderActionCells);
        return $this->objectParser->renderTable();


    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'get_entable';
    }

    public function initRuntime(Twig_Environment $environment)
    {
        // TODO: Implement initRuntime() method.
    }

    public function getGlobals()
    {
        // TODO: Implement getGlobals() method.
    }
}
