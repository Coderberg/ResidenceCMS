![GitHub](https://img.shields.io/github/license/mashape/apistatus.svg)

ResidenceCMS is an Open Source Property Management System built with [Symfony 4][1].

The user interface is built with [Bootstrap 4][2].


## Requirements

- PHP >= 7.1.3;
- PDO PHP Extension;
- MySQL >= 5.7;
- And the [usual Symfony application requirements][3].

## Installation

1. Install Composer (see http://getcomposer.org/download)

2. Create new project via [Composer][4]

   ```
   $ composer create-project coderberg/residence-cms mywebsite.loc
   ```
2. Enter the newly created folder

   ```
   $ cd mywebsite.loc
   ```

4. After installing you should configure your web server's document / web root to be the ```public``` directory.

5. Create an empty MySQL database

6. Modify your DATABASE_URL config in ```.env```

    ```
    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
    ```

7. Run

    ```
    $ php bin/console doctrine:migrations:migrate
    $ php bin/console doctrine:fixtures:load
    ```

8. Go to http://mywebsite.loc/admin and log in.

   ```
   login: admin
   password: admin
   ```
   
   
## Tests

1. Modify your DATABASE_URL config in ```.phpunit.xml.dist```
   
   ```
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
   ```

2. Execute this command to run tests:

   ```
   php bin/phpunit
   ```

[1]: https://symfony.com/
[2]: https://getbootstrap.com
[3]: https://symfony.com/doc/current/reference/requirements.html
[4]: https://getcomposer.org/doc/03-cli.md#create-project