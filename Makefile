PHP?=$(shell which php)
CP?=$(shell which cp)

setup:
	$(PHP) -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"

install: setup
	$(PHP) composer.phar install
	$(CP) app/config.php.template app/config.php

db-setup:
	$(PHP) app/setup_mysql.php
