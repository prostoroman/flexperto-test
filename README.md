Flexperto - Final Application Test
===============================

### Working demo:
http://flexperto.bs1.ru/

1. Signup
2. Go to profile (link will appear in top menu)
3. Edit your profile

### Requirements

The minimum requirement by this application template that your Web server supports PHP 5.4.0.

### Install

Extract the files to a directory that is directly under the Web root.

Run command init to initialize the application with a specific environment.
Create a new database and adjust the components['db'] configuration in common/config/main-local.php accordingly.
Apply migrations with console command yii migrate. This will create tables needed for the application to work.
Set document roots of your Web server:

for frontend /path/to/yii-application/frontend/web/

To login into the application, you need to first sign up, with any of your email address, username and password.
Then, you can login into the application with same email address and password at any time.

### Tests
I've created 1 acceptance test to check user profile and edit mobile phone
To run tests open [tests/](tests/) folder and follow instructions.

### The initial task
https://bitbucket.org/designtesbrot/flexperto-fat

###TODO
1. Change image width and height after upload using Imaging library.
2. Acceptance test to check passwords, check file upload and test form with incorrect values.
