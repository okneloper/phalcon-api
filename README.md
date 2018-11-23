# Phalcon API
Example chat API built with Phalcon framework

# Install and run
Clone the repo 
```bash
git clone git@github.com:okneloper/phalcon-api.git
```
Install composer deps:
```bash
cd phalcon-api/
composer install
```

start docker
```bash
docker-compose up -d
```

log in to the `php` container 
```bash
# (depends on the environment)
```

change directory to the site root:
```bash
cd /usr/share/nginx/html/
```

seed the database:
```bash
php cli/seed.php
```

run the tests:
```bash
php vendor/codeception/codeception/codecept run
```
