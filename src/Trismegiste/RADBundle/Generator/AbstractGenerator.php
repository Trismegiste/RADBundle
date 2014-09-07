<?php

/*
 * RADBundle
 */

namespace Trismegiste\RADBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;

/**
 * AbstractGenerator is an abstract generator OCP
 */
abstract class AbstractGenerator
{

    protected $filesystem;
    protected $skeletonDirs;

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->setSkeletonDirs($skeletonDir);
    }

    /**
     * I have to copy-paste these 3 methos from the sensio generator because
     * it's not OCP, I cannot extends twig after its creation.
     * 
     * @param string $skeletonDirs
     */
    public function setSkeletonDirs($skeletonDirs)
    {
        $this->skeletonDirs = is_array($skeletonDirs) ? $skeletonDirs : array($skeletonDirs);
    }

    protected function render($template, $parameters)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem($this->skeletonDirs), array(
            'debug' => true,
            'cache' => false,
            'strict_variables' => true,
            'autoescape' => false,
        ));

        $this->customizeTwig($twig);

        return $twig->render($template, $parameters);
    }

    protected function renderFile($template, $target, $parameters)
    {
        $this->filesystem->dumpFile($target, $this->render($template, $parameters));
    }

    protected function customizeTwig(\Twig_Environment $twig)
    {
        
    }

}