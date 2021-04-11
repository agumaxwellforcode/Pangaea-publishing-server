
## About The assignment

For this challenge we'll be creating a HTTP notification system. A server (or set of servers) will keep track of topics ->
subscribers where a topic is a string and a subscriber is an HTTP endpoint. When a message is published on a topic, it
should be forwarded to all subscriber endpoints


## Introduction to the app


Hello, I am the publisher and my job is to do the following

 - Register topics,
 - Register Subscribers,
 - Add messages to topics and publish them to subscribers.

## Technology and resources

-I was built with the following resources
 - Laravel 8,
 - MySql database,
 - Queues (database) => checkout to asynchronous-approach branch,
 - Guzzle HTTP client,

### Setup and Installation ( Publisher )

Run the following commands and settings

The application is not containerized, hence the manual commands.

 - composer install
 - Set .env variables (see .env settings)
 - php artisan migrate
 - php artisan db:seed
 - php artisan queue: work :: asynchronous-approach branch
 - npm install
 - npm run dev
 - php artisan serve

 ### Setup and Installation ( Subscribers )

Run the following commands and settings

The application is not containerized, hence the manual commands.

 - cd Subscribers/subcriber-0n => (n = 1 or 2)
 - composer install
 - Set .env variables, refer to ENV variables on publisher home page
 - php artisan migrate
 - php artisan db:seed
 - php artisan websockets:serve to start listening
 - npm install
 - npm run dev
 - php artisan serve --port {any port of choice} except publisher port

## Main Endpoints
Endpoints to use (Basic to achieve the task)

Create a topic :: payload => topic : string

post::http://{host:port}/api/topics :: {"topic":"I love pangaea"}

Get/retrieve a topic :: parameter => topic id : integer

get::http://{host:port}/api/topics/{topic} // 1, 5, 67, ...

Subscribe to a topic :: parameter => topic id : integer , payload => url

post::http://{host:port}/api/subscribe/{topic} :: {"url":"localhost:5000"}

Add a message and publish to subscribers of a topic :: parameter => topic id , payload => message

post::http://{host:port}/api/publish/{topic} :: {"message":"Pangaea ..."}

## Tests

Barely 20 % of all functionalities are tested for lack of time;
Run  ==>  php artisan test

## Demo
https://drive.google.com/file/d/1ZCzl-vPqBwPf9ncF8UAFrXuhz3FOgAaw/view

