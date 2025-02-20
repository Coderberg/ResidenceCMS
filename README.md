![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Coderberg/ResidenceCMS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Coderberg/ResidenceCMS/?branch=master) [![Testing with MySQL](https://github.com/Coderberg/ResidenceCMS/actions/workflows/mysql.yml/badge.svg)](https://github.com/Coderberg/ResidenceCMS/actions/workflows/mysql.yml) [![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=Coderberg_ResidenceCMS&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=Coderberg_ResidenceCMS)

ResidenceCMS is a fast and lightweight Property Management System on top of [Symfony 7][1].

![GitHub](https://raw.githubusercontent.com/Coderberg/ResidenceCMS/master/docs/images/screenshot.png)

## Requirements

- PHP 8.2.0 or higher;
- PDO PHP Extension;
- GD PHP extension
- MySQL >= 5.7;
- And the [usual Symfony application requirements][2].

## Installation

1. Install Composer (see http://getcomposer.org/download)

2. Create new project via [Composer][3]

   ```
   $ composer create-project coderberg/residence-cms mywebsite.loc
   ```
3. Enter the newly created folder

   ```
   $ cd mywebsite.loc
   ```

4. After installing, you should configure your web server's document / web root to be the ```public``` directory.

5. Create ```.env.local``` from ```.env``` file and fill in your database credentials

    ```
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    ```

6. Run

    ```
    $ php bin/console app:install
    ```

7. Go to http://mywebsite.loc/en/admin and log in.

   ```
   login: admin
   password: admin
   ```

8. To use feedback forms, configure MAILER_DSN in ```.env.local```

## Tests

1. Modify your DATABASE_URL config in ```.env.test.local```

   ```
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
   ```

2. Install the ChromeDriver:

   ```
   vendor/bin/bdi detect drivers
   ```

3. Execute this command to run tests:

   ```
   php bin/phpunit
   ```

## Additional documentation
- [How to run Residence CMS in Docker Containers][4]

[1]: https://symfony.com/
[2]: https://symfony.com/doc/current/setup.html#technical-requirements
[3]: https://getcomposer.org/doc/03-cli.md#create-project
[4]: https://github.com/Coderberg/ResidenceCMS/blob/master/docs/docker.md
