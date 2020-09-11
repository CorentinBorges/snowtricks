# SnowTricks Project

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9396381189da418d9e584fc9f98876d8)](https://app.codacy.com/manual/CorentinBorges/projet_5?utm_source=github.com&utm_medium=referral&utm_content=CorentinBorges/projet_5&utm_campaign=Badge_Grade_Dashboard)

This is a blog for trick's lovers !

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

## Built With
*   [Symfony 5.1](https://symfony.com/)
*   [Composer 1.10.5](https://getcomposer.org/)
*   [Twig 3.0.3](https://twig.symfony.com/)
*   [Doctrine 3.3](https://www.doctrine-project.org/index.html)
*   [Mailer 5.1](https://symfony.com/doc/current/mailer.html)

