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
      $options = $this->composer->getPackage()->getExtra();
      /*TODO: find a better way of getting this! */
      $root_path = realpath($config->get('vendor-dir')."/../..");
      $check = realpath($root_path."/".$options['magento-root-dir'])."/magesrc_installed";
      if (!file_exists($check)){
        touch($check);
        //echo "vendor path: ".$config->get('vendor-dir');
        //echo "root: ".$root_path."\n";

        $extensionInstaller = new ExtensionInstaller($root_path,$options);
        $extensionInstaller->runInstaller();
        $srcInstaller = new SrcInstaller($root_path,$options);
        $srcInstaller->runInstaller();
      }
      //var_dump($this->composer->getPackage()->getExtra());
    }
}
