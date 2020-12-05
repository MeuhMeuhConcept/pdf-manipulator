<?php

namespace Mmc\PdfManipulator\Bridge\Symfony\MmcPdfManipulatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder
            ->root('mmc_pdf_manipulator', 'array')
                ->children()
                    ->scalarNode('pdfseparate_binary')
                        ->defaultValue('pdfseparate')
                    ->end()
                    ->scalarNode('pdfunite_binary')
                        ->defaultValue('pdfunite')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder->buildTree();
    }
}
