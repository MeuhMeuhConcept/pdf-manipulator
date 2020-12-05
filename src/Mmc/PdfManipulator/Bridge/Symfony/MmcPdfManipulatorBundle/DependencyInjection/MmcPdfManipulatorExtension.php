<?php

namespace Mmc\PdfManipulator\Bridge\Symfony\MmcPdfManipulatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MmcPdfManipulatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->process($configuration->getConfigTree(), $configs);

        $container->setParameter('mmc_pdf_manipulator.pdfseparate_binary', $config['pdfseparate_binary']);
        $container->setParameter('mmc_pdf_manipulator.pdfunite_binary', $config['pdfunite_binary']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('service.yml');
    }
}
