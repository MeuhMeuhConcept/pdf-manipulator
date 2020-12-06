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

        $container->setParameter('mmc_pdf_manipulator.pdfinfo_binary', $config['pdfinfo_binary']);
        $container->setParameter('mmc_pdf_manipulator.pdfseparate_binary', $config['pdfseparate_binary']);
        $container->setParameter('mmc_pdf_manipulator.pdfunite_binary', $config['pdfunite_binary']);
        $container->setParameter('mmc_pdf_manipulator.env', $config['env']);
        $container->setParameter('mmc_pdf_manipulator.process_timeout', $config['process_timeout']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        if (!empty($config['temporary_folder'])) {
            $container->findDefinition('Mmc\PdfManipulator\Component\FileManager\LocalFileManager')
                ->addMethodCall('setTemporaryFolder', [$config['temporary_folder']]);
        }
    }
}
