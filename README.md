# magesrc
Install magento extensions and custom code with composer

use in conjunction with https://github.com/AydinHassan/magento-core-composer-installer

Put your custom code in /src

3rd party extensions in /extensions (like /extensions/foo/bar/app/code/local/.....)

add the following to your composer.json:

```
"require": {
    "tallpaul/magesrc": "*",
    "aydin-hassan/magento-core-composer-installer": "^1.0",
    "openmage/magento-lts": "1.9.3.x-dev"
},
"repositories": [
  {
    "type": "composer",
    "url": "https://packages.firegento.com"
  },
  {
    "type": "vcs",
    "url": "git@github.com:OpenMage/magento-lts.git"
  },
  {
    "type": "vcs",
    "url": "git@github.com:TallPaul/magesrc.git"
  }
],
"extra": {
    "magento-src-dir": "src",
    "magento-extension-dir": "extensions",
    "magento-root-dir": "http"
}
```

run composer update

order of installation:

Magento core copied into http
3rd party extensions copied over the top
custom code symlinked over the top

You can then work on files in src as they're symlinked into http
