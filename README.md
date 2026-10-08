# Portfolio website (PHP + MySQL)

index.html            home page (static)
register.php          create an account
login.php / logout.php
messages.php          CREATE + READ messages
edit_message.php      UPDATE a message
delete_message.php    DELETE a message
includes/             config.php (database), header.php, footer.php
css/style.css  js/main.js  assets/images/
database.sql          import this first

Run locally: install XAMPP, copy this folder to htdocs/portfolio, import database.sql in phpMyAdmin,
then open http://localhost/portfolio/. Edit includes/config.php if your MySQL login differs.
Make yourself admin by running the UPDATE line at the bottom of database.sql.
