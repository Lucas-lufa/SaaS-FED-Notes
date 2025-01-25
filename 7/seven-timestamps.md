eight-timestamps

2 55
Change the tailwind so it doesn't look in the node module and vendor folders

7 07
composer install && composer update

12 12
npm install && npm update

14 35
for tailwind release use the --build instead of --watch

17 00
index.php

26 30
views/partials
header

30 10
footer

31 40
navigation

40 34 
message

43 07
error

46 00
general views home and error

52 10
controllers
HomeController
1 01 20
ErrorController
1 04 41
UserController

2 28 35
Showing app

2 33 48
ProductController

## 7
1 24 09
npm install -D tailwindcss
installs tailwind form npm in developer mode.

1:29:10
touch composer.json this is used for autoloading, search through folders for particular classes and include them dynamically when needed. saves when the classes are for quick references.

 1:32:10 keywords?
 "minimum-stability": "stable",if include any other pbp need to be the stable forms

 1:36:10
 psr-4 is an auto loader there are different one that auto load different things "php standards request" maybe
 
 1 38 15
 composer install 
 creates a composer.lock file and a vendor folder

1:39:30 mod .gitignore

1:46:30 public folder
add a .htaccess file this file is used by apache to do url rewrite can also add permissions.
rewrites the url sent to the web request into another form to looks for something in the file system or such.

1 53 35
config/db.php

1 55 59
helper functions

2 44 14
classes Database

2 59 00
class router

3 27 20
class sessions

3 42 15
class authorisation

3 46 40
class validation

3 53 07
class authorise