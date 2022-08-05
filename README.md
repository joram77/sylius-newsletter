<h1 align="center">Sylius Newsletter</h1>

<p align="center">Newsletter system with multiple subscriptions per customer. This project contains a Sylius installation
and the plugin JoramSyliusNewsletterPlugin. </p>

Installation
------------
Standard environment that will be setup is staging.
Set your database credentials in doctrine.yaml
```yaml
doctrine:
    dbal:
        password: 'test'
```
Set your smtp server, for example to use mailhog local smtp on port 1025:
```env
MAILER_DSN=smtp://localhost:1025
```
Then install sylius:
```bash
$ composer install
$ yarn install
$ yarn build
$ php bin/console sylius:install
$ symfony serve
$ open http://localhost:8000/
```

Usage
------------
### Creating a newsletter with subscribers
1. Go to /admin of your site, login
1. On the left menu: Catalog > Newsletters > Create
1. On the left menu: Customer > Customers > edit customer(s) to subscribe > check subscriptions

### Subscribe to newsletter(s) as a customer
On the shop via front /en_US/account/profile/edit
My Account > Left menu Personal Information > Right panel: subscription toggles

### Sending a newsletter
1. Queue & prepare mails\
sending a newsletter as admin, will be queued to messenger
    ```bash
    php bin/console --env=staging joram:newsletter:send 2
    ```
2. Send mails in queue\
 then start in cli (or run via background job) to send e-mails in queue via smtp transport
    ```bash
    php bin/console messenger:consume async
    ```
Authors
-------

Joram Declerck
