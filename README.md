A custom store module for [frugue.com](https://frugue.com).

## How to install
```
bin/magento maintenance:enable
composer clear-cache
composer require frugue/store:*
bin/magento setup:upgrade
rm -rf var/di var/generation generated/code && bin/magento setup:di:compile
rm -rf pub/static/* && bin/magento setup:static-content:deploy en_US de_DE fr_FR --area frontend --theme TemplateMonster/theme007 && bin/magento setup:static-content:deploy en_US --area adminhtml --theme Magento/backend
bin/magento maintenance:disable
```

## How to upgrade
```
bin/magento maintenance:enable
composer clear-cache
composer update frugue/store
bin/magento setup:upgrade
rm -rf var/di var/generation generated/code && bin/magento setup:di:compile
rm -rf pub/static/* && bin/magento setup:static-content:deploy en_US de_DE fr_FR --area frontend --theme TemplateMonster/theme007 && bin/magento setup:static-content:deploy en_US --area adminhtml --theme Magento/backend
bin/magento maintenance:disable
```

If you have problems with these commands, please check the [detailed instruction](https://mage2.pro/t/263).