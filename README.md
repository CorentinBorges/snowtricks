# SnowTricks Project

<a href="https://codeclimate.com/github/CorentinBorges/snowtricks/maintainability"><img src="https://api.codeclimate.com/v1/badges/41f82ca455bdf5dc12fd/maintainability" /></a>

This is a blog for trick's lovers !

## Required
* Bootstrap 4.5.0
* Symfony 4.18
* Jquery 3.5.1
* WAMP Server
    * PHP 7.4
    * MySQL 5.7
    * composer 1.10

## Installing project

1.  Download:
    ```bash
    $ git clone https://github.com/CorentinBorges/snowtricks.git
    ```

2.  Install:
    ```
    $ composer install
    ```

3.  Configure your database in [.env](.env) 
    ```
    DATABASE_URL= mysql://username:password@host:port/dbName?serverVersion=5.7
    ```
    
4.  Create the database and export fixtures (pass for all users: 'snowPass' )
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migration:migrate
    php bin/console doctrine:fixtures:load
    ```

5.  Configure your mail sender in  [.env](.env) (this is an exemple for gmail users) :
    ```
    MAILER_DSN=gmail://yourMail:yourPassword@host
    ```
    
6. Connect the website locally with symfony:
    ```
    symfony serve -d
    ```
Your url must be 127.0.0.1:8000 if you don't have any other projects running with symfony at the same time.


## Built With
*   [Symfony 5.1](https://symfony.com/)
*   [Composer 1.10.5](https://getcomposer.org/)
*   [Twig 3.0.3](https://twig.symfony.com/)
*   [Doctrine 3.3](https://www.doctrine-project.org/index.html)
*   [Mailer 5.1](https://symfony.com/doc/current/mailer.html)

