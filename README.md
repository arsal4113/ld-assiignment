# Arslaan Muhammad Ali
## Coding Assignment PHP

[![N|Solid](https://www.lillydoo.com/bundles/lepweb/img/lillydoo-logo-dark.svg)](https://www.lillydoo.com/)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

# lillydoo Test
Implement a RESTful API for before-mentioned phone-book to be used by
our frontend developers. Customers should be able to perform the following actions

### Project specifications:
- Add other customers as contact.
- Edit created contacts.
- Delete existing contacts
- Search for contacts by name

## Instruction to Run
- Clone the repo
- Ensure your PHP version is at least 8.0
- Run cp .env.example .env
- Run composer install
- Run php bin/console doctrine:migrations:migrate to run migration and add --env=test flag for test migration
- If you have issues with migrating the env test, run php bin/console doctrine:database:create --env=test to create the test db
- Run php bin/console doctrine:fixtures:load to load data fixtures add the test env flag to load for test db
- Run symfony server:start

Once the project is up and running on http://localhost
