# The new project  
This project provides an API for hotels to get their reviews and the average score.
Also, for chain hotels it should be possible to get a list of their hotels. A chain hotel is a collection of hotels that belong to one group.

# Todo
- We need to refactor the project and make it future proof. 
- Chain hotels are not defined currently. We need to implement that.
- Also, one of our customers wants to have a javascript widget that he can embed in their website.
  The widget should show an average score of all their reviews that have been created/submitted within the last year rounded to an integer. 
  The widget could consume the averages API, that we are providing. The Hotel can potentially have thousands of reviews, so keep that in mind for performance considerations.

The hotelier should be able to embed their widget by simply pasting a snippet like this before the closing </body> tag of their website:

`<script src="http://host-of-the-app/widget/{{UUID}}.js"></script>`

Where {{UUID}} is the uuid of the Hotel. To keep this task simple we are not generating other hashes or access keys for using this widget but simply stick to the UUID.
The response can be cached by clients for up to 1 hour.

# Setup
- composer install
- create schema
- load fixtures
- use the `symfony serve` or the builtin php server for development
