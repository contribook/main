# CONTRIBOOK


## What is CONTRIBOOK
Contribook is a PHP module that is designed to be included in websites to show the users or community that is active on this website or project. This can be used by free software project to show who is part of the project. The name is a meant as a joke and is a combination of Contributors and a well known Social network. So it is a social network for free software project contributors. The goal is of course not to build a full social network but to show visitors of the websites who contributes to the project, how to communicate with them,  what is going on and how to become part of it. This is inspired by the need of the ownCloud.org community and also the result long discussion in the KDE community. Some of the code is a reuse of some code that I wrote years ago for the KDE.org homepage.


## Features
* Showing the members of the community
* Show a profile page of every user
* Show the latest blog posts of the users (planert)
* Show the latest Twitter posts of the users
* Show the latest forum posts
* Show the latest apps/themes from an OCS server
* Show the latest news from a RSS feed.


## Requirements
* A webserver. Recommended is Apache on Linux
* PHP 4.3 or newer
* MySQL


## Installation
* Put the contribook subfolder on your webserver.
* Create a mysql database for contribook. User the mysql.dmp as example.
* Include the lib_contribook.php from your php pages where you want to show the content.
* Create a config.php as adapt the settings. config.sample.php can be used as a template.
* Create the contributor.php file using the contributor.sample.php file as a template. This will be replaced by a database backend in the future.
* Create the templates that you want to use to show the content. The shipped templates folder and the style.css can be used as examples
* Call the individual show function to output the data on the pages you want. The shipped index.php is an example how to do that.
* Add a cronjob that calls cronjob.php every few minutes. Recommended is 15min


## Todo
This is only a rough first release. The Todos are tracked in todo.md Everybody is welcome to contribute. 


## Contribute
If you want to contribute please open a pull request here on github. Every improvement is more than welcome.


## License
Contribook is released under the AGPL v2 license



Frank Karlitschek
frank@karlitschek.de

