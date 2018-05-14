<?php
namespace TallPaul;



class ExtensionInstaller {
    protected $src_root;
    protected $dest_root;

    public function __construct($root_path,$options){
      $this->src_root = realpath($root_path."/". $options['magento-extension-dir']);
      $this->dest_root = realpath($root_path."/".$options['magento-root-dir']);
    }

    public function runInstaller(){
      $extensions = array();
      $extension_namespaces = glob($this->src_root."/*",GLOB_ONLYDIR);
      foreach($extension_namespaces as $dir){
        $extension_dirs = glob($dir."/*",GLOB_ONLYDIR);
        foreach($extension_dirs as $dir2){
          $extensions[] = $dir2;
        }
      }
      foreach($extensions as $extension){
        $extension_name = str_replace($this->src_root,'',$extension);
        echo "installing ".$extension_name."\n";
        $extension_namespace_iterator = new \RecursiveDirectoryIterator($extension);
        foreach(new \RecursiveIteratorIterator($extension_namespace_iterator,\RecursiveIteratorIterator::SELF_FIRST) as $file){
            if (stristr($file,'/.')) //ignore /. and /..
              continue;
            $filepath = str_replace($extension,'',$file);
            $src = $file;
            $target = $this->dest_root.str_replace($extension,'',$file);
            //shouldn't have any symlinks yet!
            if (is_dir($src)){
              if (file_exists($target)){
                continue;
              } else {
                mkdir($target);
              }
            } else {
              if (file_exists($target)){
                unlink($target);
              }
              copy($src,$target);
            }

        }
      }

    }
}

?>
