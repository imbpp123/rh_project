# The new project  
This project provides an API for hotels to get their reviews and the average score.
Also, for chain hotels it should be possible to get a list of their hotels. A chain hotel is a collection of hotels that belong to one group.

## Todo
- We need to refactor the project and make it future proof. 
- Chain hotels are not defined currently. We need to implement that.
- Also, one of our customers wants to have a javascript widget that he can embed in their website.
  The widget should show an average score of all their reviews that have been created/submitted within the last year rounded to an integer. 
  The widget could consume the averages API, that we are providing. The Hotel can potentially have thousands of reviews, so keep that in mind for performance considerations.

The hotelier should be able to embed their widget by simply pasting a snippet like this before the closing </body> tag of their website:

`<script src="http://host-of-the-app/widget/{{UUID}}.js"></script>`

Where {{UUID}} is the uuid of the Hotel. To keep this task simple we are not generating other hashes or access keys for using this widget but simply stick to the UUID.
The response can be cached by clients for up to 1 hour.

## Setup
1. Install docker and docker-compose to your system 
1. git-clone project to disk
1. Run `$ make build` in project folder. This command builds containers, php packages and initialize database 
1. Run `$ make test` in project folder to run tests
1. Run `$ make up` in project folder to start project

## UUID pixel howto
In order to test if pixel works, you need to place uuid from database to `public/test.html` file. 

1. Follow all steps in setup
1. Use database credentials from docker-compose.yml file to login into MySQL instance and get hotel UUID
1. Add this UUID instead of current one to `public/test.html` file in `<script src="/widget/1fa16f6e-35eb-481f-b56c-fce84a8cd595.js" id="pixelScript"></script>`
1. Open `http://localhost/test.html` in any browser

## Usefull commands

* `make build` builds project
* `make init` executes migrations and fill database with fixtures
* `make test` runs tests
* `make up` runs the project. It can not work in parallel with tests
* `make build-app` builds php-fpm container
* `make build-apache` builds apache container
