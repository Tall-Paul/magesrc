<?php
namespace TallPaul;

use Composer\Util\Filesystem;

class SrcInstaller {
    protected $src_root;
    protected $dest_root;

    public function __construct($root_path,$options){
      $this->src_root = realpath($root_path."/". $options['magento-src-dir']);
      $this->dest_root = realpath($root_path."/".$options['magento-root-dir']);
      $this->src_path = $options['magento-src-dir'];
    }

    public function runInstaller(){
      $filesystem = new Filesystem;

      echo "installing src...\n";
      $src_iterator = new \RecursiveDirectoryIterator($this->src_root);
      $symlinked = array();
      foreach(new \RecursiveIteratorIterator($src_iterator,\RecursiveIteratorIterator::SELF_FIRST) as $file){
        if (stristr($file,'/.')) //ignore /. and /..
          continue;
        $filepath = str_replace($this->src_root,'',$file);
        /*this is all to avoid recursing into directories once they're symlinked*/
        $skip = false;
        foreach($symlinked as $link){

          if (stristr($filepath,$link)){
            $skip = true;
            break;
          }
        }
        if ($skip == true){
          continue;
        }
        /**/

        $depth = substr_count($filepath,DIRECTORY_SEPARATOR);
        $i = 0;
        $link_src = "..";
        while ($i < ($depth - 1)){
          $link_src .= "/..";
          $i++;
        }
        $link_src =  $link_src ."/".$this->src_path.$filepath;
        $src = $file;
        $target = $this->dest_root.str_replace($this->src_root,'',$file);
        if (is_dir($src)){
          if (file_exists($target)){
            continue;
          } else {
            $filesystem->relativeSymlink($src,$target);
            echo "-";
            $symlinked[] = $filepath;
          }
        } else {
          if (is_file($src)){
            if (file_exists($target)){
              unlink($target);
            }
            echo "-";
            $filesystem->relativeSymlink($src,$target);
          }
        }


       }
       echo "\n";
    }
}

?>
