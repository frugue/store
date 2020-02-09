A custom store module for [frugue.com](https://frugue.com).

## How to install
```              
sudo service cron stop     
sudo service nginx stop      
sudo service php7.2-fpm stop 
bin/magento maintenance:enable
rm -rf composer.lock
composer clear-cache
composer require frugue/store:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme TemplateMonster/theme007 \
	-f en_US de_DE fr_FR ru_RU
bin/magento maintenance:disable
sudo service php7.2-fpm start
sudo service nginx start
sudo service cron start
```

## How to upgrade
```      
sudo service cron stop     
sudo service nginx stop      
sudo service php7.2-fpm stop 
bin/magento maintenance:enable
composer remove frugue/store
rm -rf composer.lock
composer clear-cache
composer require frugue/store:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme TemplateMonster/theme007 \
	-f en_US de_DE fr_FR ru_RU
bin/magento maintenance:disable
sudo service php7.2-fpm start
sudo service nginx start
sudo service cron start
```