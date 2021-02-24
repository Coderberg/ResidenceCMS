## How to run Residence CMS  in Docker Containers ##

1. Clone repositories
```
$ git clone https://github.com/eko/docker-symfony.git
$ cd docker-symfony
$ git checkout 5cc2522
$ git clone https://github.com/Coderberg/ResidenceCMS.git symfony
```

2. Create ```.env``` files
```
$ echo -e '
DATABASE_URL=mysql://symfony:symfony@db/symfony?serverVersion=5.7\r
LANGUAGE_CODE=en' > ./symfony/.env.local

$ echo -e "\
KERNEL_CLASS='App\Kernel'\r\
APP_SECRET=34c32c8b0a7cc951fd105659f77ec1b6\r\
SYMFONY_DEPRECATIONS_HELPER=999999\r\
PANTHER_APP_ENV=panther\r\
DATABASE_URL=mysql://symfony:symfony@db/symfony\r\
" > ./symfony/.env.test.local

```

3. Run containers
```
$ docker-compose up -d
$ docker exec -it php-fpm /bin/sh
```

4. Install app
```
# composer install
# php bin/console app:install
# find ./public/uploads -type d -print | xargs chmod 777

# php bin/phpunit
```

5. Go to http://symfony.localhost
