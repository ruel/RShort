#ABOUT

RShort is a simple URL shortener written in PHP. To use the script, you must have a html page to pass the post data. The hash it provides is random (sort of) and has a length of 5. The script only returns either the shortened URL or an error, so this can be used on different applications. You can see a demo on: http://ruel.me/rshort

#INSTALLING

To install and use this script, first you must edit the .htaccess file on the directory where the hash will originate from. Example, if your desired url looks like 'http://example.com/r5Tyu', the .htaccess to be edited must be on the site's root/home directory. Add these lines at the top of your .htaccess file:

<<<<<<< HEAD:README.markdown
	RewriteEngine on
	RewriteRule ^([a-zA-Z0-9]{5})$ rshort.php?hash=$1

**NOTE**

If you are installing the script at a subdirectory, include the subdirectory path unless the .htaccess is placed there aswell.

Next, create a database and create a table using this:

	CREATE TABLE rshort (
	  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  url VARCHAR(2083),
	  hash VARCHAR(10) NOT NULL UNIQUE,
	  created TIMESTAMP DEFAULT NOW()
	);

Then, edit the CONFIG part on the script itself. Change the following variables:

	// Start CONFIG
=======
[code,text]
----------------------------------------------
RewriteEngine on
RewriteRule ^([a-zA-Z0-9]{5})$ rshort.php?hash=$1
----------------------------------------------

.NOTE
- If you are installing the script at a subdirectory, include the subdirectory path unless the .htaccess is placed there aswell.

Next, create a database and create a table using this:

[code,mysql]
----------------------------------------------
CREATE TABLE rshort (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  url VARCHAR(2083),
  hash VARCHAR(10) NOT NULL UNIQUE,
  created TIMESTAMP DEFAULT NOW()
);
----------------------------------------------

Then, edit the CONFIG part on the script itself. Change the following variables:

[code,php]
----------------------------------------------
// Start CONFIG
>>>>>>> c98661213f38ce3725aac8ab1d4b36977e43a54d:README.asciidoc

	// DATABASE
	$dbserver 	= '';
	$dbuser 	= '';
	$dbpass 	= '';
	$dbname 	= '';

	// SITE NAME (With trailing forward slash)
	$sitename 	= '';

<<<<<<< HEAD:README.markdown
	// End CONFIG
=======
// End CONFIG
----------------------------------------------
>>>>>>> c98661213f38ce3725aac8ab1d4b36977e43a54d:README.asciidoc

#CONTACT

If there are questions or comments, and you want to talk to me personally, contact me at ruel[ @ ]ruel.me.

#TODO

- Might aswell add more features to the script.
- Add a custom hash feature.
- Improve the hashing.

#LICENSE

RShort is Copyright (C) 2010 Ruel Pagayon - http://ruel.me

This code is licensed to you under the terms of the GNU GPL, version 3; see:
 http://www.gnu.org/licenses/gpl-3.0.txt
 