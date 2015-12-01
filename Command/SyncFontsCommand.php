<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\Command;

use Mopa\Bridge\Composer\Adapter\ComposerAdapter;
use Mopa\Bridge\Composer\Util\ComposerPathFinder;
use opwoco\Bundle\BootstrapBundle\Constant\IconSet;
use Sabberworm\CSS\CSSList\Document;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sabberworm\CSS\Parser;

/**
 * Command to index all iconIdentifiers from the fontSets.
 */
class SyncFontsCommand extends ContainerAwareCommand
{


    public static $ignoredIdentifiers = array(
        IconSet::GLYPHICON => array('carousel-control'),
        IconSet::FONTAWESOME => array('fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-ul', 'fa-li', 'fa-border', 'fa-pulse', 'fa-spin', 'fa-rotate', 'fa-flip', 'fa-stack', 'fa-inverse'),
        IconSet::FOUNDATION => array(),
        IconSet::IONICONS => array(),
        IconSet::OCTICONS => array(),
        IconSet::MATERIAL => array('mdi-lg, mdi-2x', 'mdi-size-2x', 'mdi-3x', 'mdi-size-3x', 'mdi-4x', 'mdi-size-4x', 'mdi-5x', 'mdi-size-5x', 'mdi-fw', 'mdi-ul', 'mdi-li', 'mdi-border',
            '.pull-left', 'pull-right', 'mdi.pull-left', 'mdi.pull-right', 'mdi-spin', 'mdi-pulse', 'mdi-rotate-90', 'mdi-rotate-180', 'mdi-rotate-270', 'mdi-flip-horizontal', 'mdi-flip-vertical',
            'mdi-stack', 'mdi-stack-1x, .mdi-stack-2x', 'mdi-stack-2x', 'mdi-inverse'),
    );

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('opwoco:bootstrap:sync:fonts')
            ->setDescription("Install create or update table of font identifiers")
            ->addOption(
                IconSet::GLYPHICON,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set glyphicon icons will be indexed in DB',
                false
            )
            ->addOption(
                IconSet::FONTAWESOME,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set fontawesome icons will be indexed in DB',
                false
            )
            ->addOption(
                IconSet::FOUNDATION,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set fundation icons will be indexed in DB',
                false
            )
            ->addOption(
                IconSet::IONICONS,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set ionicons icons will be indexed in DB',
                false
            )
            ->addOption(
                IconSet::OCTICONS,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set octicons icons will be indexed in DB',
                false
            )
            ->addOption(
                IconSet::MATERIAL,
                null,
                InputOption::VALUE_OPTIONAL,
                'Set "false" by default, if set material icons will be indexed in DB',
                false
            )
            ->setHelp(<<<EOT
The <info>opwoco:bootstrap:install:fonts</info> command creates the DB table for all font-sets to provide a multiple lookup

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $indexGlyphicon = $input->getOption(IconSet::GLYPHICON);
        $indexFontawesome = $input->getOption(IconSet::FONTAWESOME);
        $indexFoundation = $input->getOption(IconSet::FOUNDATION);
        $indexIonicons = $input->getOption(IconSet::IONICONS);
        $indexOcticons = $input->getOption(IconSet::OCTICONS);
        $indexMaterial = $input->getOption(IconSet::MATERIAL);

        if (!$indexGlyphicon && !$indexFontawesome && !$indexFoundation && !$indexIonicons && !$indexOcticons && !$indexMaterial) {
            $indexGlyphicon = true;
            $indexFontawesome = true;
            $indexFoundation = true;
            $indexIonicons = true;
            $indexOcticons = true;
            $indexMaterial = true;
        }

        // Building root path for all fontSets
        $composer = ComposerAdapter::getComposer($input, $output);
        $cmanager = new ComposerPathFinder($composer);
        $sourcePackage = $cmanager->findPackage('opwoco/bootstrap-bundle');
        $fontsPath = $composer->getInstallationManager()->getInstallPath($sourcePackage). '/Resources/public/css/icon-fonts/';

        $output->writeln("starting to parse css files");

        if ($indexGlyphicon) {
            $cssParser = new Parser(file_get_contents($fontsPath . '../../bootstrap/dist/css/bootstrap.css'));
            $output->writeln("starting to parsing glyhicons");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::GLYPHICON, $output);
            $this->flushData($identifers, IconSet::GLYPHICON);
        }
        if ($indexFontawesome) {
            $cssParser = new Parser(file_get_contents($fontsPath . 'font-awesome/font-awesome.css'));
            $output->writeln("starting to parsing font-awesome");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::FONTAWESOME, $output);
            $this->flushData($identifers, IconSet::FONTAWESOME);

        }
        if ($indexFoundation) {
            $cssParser = new Parser(file_get_contents($fontsPath . 'foundation-icons/foundation-icons.css'));
            $output->writeln("starting to parsing foundation");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::FOUNDATION, $output);
            $this->flushData($identifers, IconSet::FOUNDATION);

        }
        if ($indexIonicons) {
            $cssParser = new Parser(file_get_contents($fontsPath . 'ion-icons/ionicons.css'));
            $output->writeln("starting to parsing ionicons");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::IONICONS, $output);
            $this->flushData($identifers, IconSet::IONICONS);

        }
        if ($indexOcticons) {
            $cssParser = new Parser(file_get_contents($fontsPath . 'octicons/octicons.css'));
            $output->writeln("starting to parsing octicons");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::OCTICONS, $output);
            $this->flushData($identifers, IconSet::OCTICONS);
        }
        if ($indexMaterial) {
            $cssParser = new Parser(file_get_contents($fontsPath . 'material/material-icons.css'));
            $output->writeln("starting to parsing material");
            $cssFile = $cssParser->parse();
            $identifers = $this->parseCSS($cssFile, IconSet::MATERIAL, $output);
            $this->flushData($identifers, IconSet::MATERIAL);
        }

    }

    private function parseCSS(Document $cssFile, $fontSet, OutputInterface $output) {
        $prefixIdentifier = $this->getIdentifierPrefixByFontSet($fontSet);
        $identifiers = array();

        /** @var \Sabberworm\CSS\RuleSet\DeclarationBlock $block */
        foreach($cssFile->getAllDeclarationBlocks() as $block) {
            /** @var \Sabberworm\CSS\Property\Selector $selector */
            foreach($block->getSelectors() as $selector) {
                $identifier = $selector->getSelector();
                if (strpos($identifier, $prefixIdentifier)) {

                    $isOnIgnoreList = false;
                    foreach(self::$ignoredIdentifiers[$fontSet] as $ignored) {
                        if (strpos($identifier, $ignored) != false) {
                            $isOnIgnoreList = true;
                        }
                    }
                    if (!$isOnIgnoreList) {
                        $identifier = str_replace('.' . $prefixIdentifier, '', $identifier);
                        $identifier = str_replace(':before', '', $identifier);
                        if (!in_array($identifier, $identifiers)) {
                            $output->writeln(sprintf('  found identifier %s', $identifier));
                            $identifiers[sizeof($identifiers)] = $identifier;
                        }
                    }
                }
            }
        }
        return $identifiers;
    }

    private function flushData(array $identifiers, $fontSet) {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach ($identifiers as $identifier) {
            $em->getRepository('opwocoBootstrapBundle:BootstrapIcon')->createOrUpdateIcon($identifier, $fontSet);
        }
    }

    /**
     * Returns the adequate prefix for a specific fontSet
     * @param $fontSet
     * @return bool|string
     */
    private function getIdentifierPrefixByFontSet($fontSet) {
        switch($fontSet) {
            case IconSet::GLYPHICON: {
                return 'glyphicon-';
            }
            case IconSet::FONTAWESOME: {
                return 'fa-';
            }
            case IconSet::FOUNDATION: {
                return 'fi-';
            }
            case IconSet::IONICONS: {
                return 'ion-';
            }
            case IconSet::OCTICONS: {
                return 'octicon-';
            }
            case IconSet::MATERIAL: {
                return 'mdi-';
            }
            default: {
                return false;
            }
        }
    }
}
