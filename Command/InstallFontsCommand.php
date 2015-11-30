<?php

/*
 * This file is part of the OpwocoBootstrapBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace opwoco\Bundle\BootstrapBundle\Command;

use opwoco\Bundle\BootstrapBundle\Constant\IconSet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Mopa\Bridge\Composer\Adapter\ComposerAdapter;
use Mopa\Bridge\Composer\Util\ComposerPathFinder;

/**
 * Command to create Bootstrap symlink to OpwocoBootstrapBundle.
 */
class InstallFontsCommand extends ContainerAwareCommand
{
    public static $iconSets = array(
        IconSet::GLYPHICON => 'twbs/bootstrap',
        IconSet::FONTAWESOME => 'fortawesome/font-awesome',
    //    IconSet::FOUNDATION => '',
        IconSet::IONICONS => 'driftyco/ionicons',
    //    IconSet::OCTICONS => '',
        IconSet::MATERIAL => 'mervick/material-design-icons'
    );

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('opwoco:bootstrap:install:fonts')
            ->setDescription("Install font to BootstrapBundel/Resources/public/fonts")
            ->addOption(IconSet::GLYPHICON, 'g', InputOption::VALUE_NONE, 'Installs gylphicon icons')
            ->addOption(IconSet::FONTAWESOME, 'a', InputOption::VALUE_NONE, 'Installs fontawesome icons')
            ->addOption(IconSet::FOUNDATION, 'f', InputOption::VALUE_NONE, 'Installs foundation icons')
            ->addOption(IconSet::IONICONS, 'i', InputOption::VALUE_NONE, 'Installs ion icons')
            ->addOption(IconSet::OCTICONS, 'o', InputOption::VALUE_NONE, 'Installs octicon icons')
            ->addOption(IconSet::MATERIAL, 'm', InputOption::VALUE_NONE, 'Installs material icons')
            ->setHelp(<<<EOT
The <info>opwoco:bootstrap:install:font</info> command install the font configured to used into web/fonts directory

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->applyOptions($input);

        $composer = ComposerAdapter::getComposer($input, $output);
        $composerPathFinder = new ComposerPathFinder($composer);
        $filesystem = new Filesystem();

        $installPaths = $this->createInstallPaths($composer, $composerPathFinder, $filesystem);
        $this->copyFontsToOpwocoBootstrap($installPaths, $composer, $composerPathFinder, $filesystem, $output);
    }


    private function applyOptions(InputInterface $input)
    {
        $glyphicon = $input->getOption(IconSet::GLYPHICON);
        $fontawesome = $input->getOption(IconSet::FONTAWESOME);
        $foundation = $input->getOption(IconSet::FOUNDATION);
        $ionicons = $input->getOption(IconSet::IONICONS);
        $octicons = $input->getOption(IconSet::OCTICONS);
        $material = $input->getOption(IconSet::MATERIAL);

        if ($glyphicon || $fontawesome || $foundation || $ionicons || $octicons || $material) {

            if (!$glyphicon) {
                unset(self::$iconSets[IconSet::GLYPHICON]);
            }
            if (!$fontawesome) {
                unset(self::$iconSets[IconSet::FONTAWESOME]);
            }
            if (!$foundation) {
                unset(self::$iconSets[IconSet::FOUNDATION]);
            }
            if (!$ionicons) {
                unset(self::$iconSets[IconSet::IONICONS]);
            }
            if (!$octicons) {
                unset(self::$iconSets[IconSet::OCTICONS]);
            }
            if (!$material) {
                unset(self::$iconSets[IconSet::MATERIAL]);
            }
        }
    }


    /**
     * @param $composer
     * @param ComposerPathFinder $composerPathFinder
     * @param Filesystem $filesystem
     * @return array
     */
    private function createInstallPaths($composer,ComposerPathFinder $composerPathFinder, Filesystem $filesystem)
    {
        $sourcePackage = $composerPathFinder->findPackage('opwoco/bootstrap-bundle');
        if ($sourcePackage) {
            $opwocoBootstrapPath = $composer->getInstallationManager()->getInstallPath($sourcePackage);
            $rootInstallPath = $opwocoBootstrapPath.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR;

            $installPaths = array();

            foreach (array_keys(self::$iconSets) as $iconSet) {
                $installPathFont = $rootInstallPath . $iconSet;
                $installPaths[$iconSet] = $installPathFont;

                if (!$filesystem->exists($installPathFont)) {
                    $filesystem->mkdir($installPathFont);
                }
            }
            return $installPaths;
        }
        return null;
    }

    
    /**
     * @param $installPaths
     * @param $composer
     * @param ComposerPathFinder $composerPathFinder
     * @param Filesystem $filesystem
     * @param OutputInterface $output
     */
    private function copyFontsToOpwocoBootstrap($installPaths, $composer,ComposerPathFinder $composerPathFinder, Filesystem $filesystem, OutputInterface $output) {
        foreach(self::$iconSets as $key => $iconSetPackage) {

            $sourcePackage = $composerPathFinder->findPackage($iconSetPackage);
            if ($sourcePackage) {
                $bsbPath = $composer->getInstallationManager()->getInstallPath($sourcePackage);

                $fontPath = $bsbPath.DIRECTORY_SEPARATOR.'fonts';

                $finder = new Finder();
                $finder->files()->in($fontPath);

                foreach ($finder as $file) {
                    $filesystem->copy($file->getRealpath(), $installPaths[$key].DIRECTORY_SEPARATOR.$file->getRelativePathname());
                }

                $output->writeln('Font: '.$key.' Installed... <info>OK</info>');
            }
            else {
                $output->writeln('Font: '.$key.' Not installed... <error>NOT FOUND</error> - <info>For support: add  "'. $iconSetPackage .'" to the require block in your composer.json</info>');
            }
        }
    }
}
