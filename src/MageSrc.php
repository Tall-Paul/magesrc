<?php
namespace TallPaul;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Util\Filesystem;

class MageSrc implements PluginInterface, EventSubscriberInterface
{
    protected $composer;
    protected $io;



    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
      return array(
          'post-update-cmd' => array(
              array('runInstaller', 0)
          ),
          'post-install-cmd' => array(
              array('runInstaller', 0)
          ),
      );
    }

    public function runInstaller($event){
      $config = $this->composer->getConfig();
      /*TODO: find a better way of getting this! */
      $root_path = realpath($config->get('vendor-dir')."/../..");
      //echo "vendor path: ".$config->get('vendor-dir');
      //echo "root: ".$root_path."\n";
      $options = $this->composer->getPackage()->getExtra();
      $extensionInstaller = new ExtensionInstaller($root_path,$options);
      $extensionInstaller->runInstaller();
      $srcInstaller = new SrcInstaller($root_path,$options);
      $srcInstaller->runInstaller();
      //var_dump($this->composer->getPackage()->getExtra());
    }
}
