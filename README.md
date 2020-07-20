# TODO-WEBAPP-
Responsive Todo web-application with login, registration and email verification functionality created using HTML, CSS, PHP and MYSQL. 
By Rahul Verma.

For recieving verification email replace localhost and 192.168.1.3 by servername in register.php and change the path of verification link of email to the path where verifyemail.php 
is placed and don't forget to include "?verify=$verify" in the end of file path.

Replace localhost by servername, root by username, include password if any otherwise leave it blank in register.php, verifymail.php, login.php, resetpass.php, 
forgetaccount.php and home.php where ever connection to database is required. In case of XAMPP server by default the username is "root" and password is "" (blank) 
if you are using xampp server then you donot have to make any changes to code which makes connection to database.

You donot have to create any database TODO-WEBAPP automatically creates database and tables if not present already.

To run this web application on XAMPP local server just download the zip file and extract the file in xampp/htdocs folder. Cut all the already present files and folders and paste 
them in a new folder (name it anything like "previous files"). After starting Apache and MySQL from xampp control panel just type localhost in your web browser.
If opening in another conputer or mobile devices then make sure they all are connected to the same network.



