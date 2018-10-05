ResidenceCMS is an Open Source Property Management System built with [Symfony 4][1].

The user interface is built with [Bootstrap 4][2].


## Requirements

- PHP >= 7.1.3 or higher;
- PDO PHP Extension;
- And the [usual Symfony application requirements][3].

## Installation

1. Clone project

   ```
   $ git clone https://github.com/Coderberg/ResidenceCMS.git mywebsite
   ```
2. Enter the newly created folder

   ```
   $ cd mywebsite
   ```
3. Install dependencies with [Composer][4]

   ```
   $ composer install
   ```

4. After installing Symfony, you should configure your web server's document / web root to be the ```public``` directory.

5. Create an empty MySQL database

6. Create ```.env``` from ```.env.dist``` file and fill in your database credentials

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

[1]: https://symfony.com/
[2]: http://getbootstrap.com
[3]: https://symfony.com/doc/current/reference/requirements.html
[4]: https://getcomposer.org/doc/00-intro.md