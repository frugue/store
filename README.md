A custom store module for [frugue.com](https://frugue.com).

## How to install
```
composer require frugue/store:*
bin/magento setup:upgrade
rm -rf pub/static/* && bin/magento setup:static-content:deploy en_US de_DE fr_FR
rm -rf var/di var/generation generated/code && bin/magento setup:di:compile
```
If you have problems with these commands, please check the [detailed instruction](https://mage2.pro/t/263).