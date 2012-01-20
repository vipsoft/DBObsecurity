============
DBObsecurity
============

**DBObsecurity** is a Piwik plugin to obscure our database user's password when
stored in Piwik's config file.

Requirements:

- Piwik 1.7 (or higher)
- openssl extension for encryption (optional)

Features:

- load value from any named environment variable
- encryption using public key/private key pair cryptography

Why use DBObsecurity?
=====================
Without DBObsecurity, our MySQL database user's password is stored in plaintext
(unencrypted/unhashed) within piwik/config/config.ini.php.  As an example, our
config file might contain:

::

	[database]
	host     = "localhost"
	username = "root"
	password = "MyDatabasePassword"
	dbname   = "piwik"


With DBObsecurity, we can encrypt our password, fetch our password from an
environment variable set by the web server, or even combine these methods!
In fact, we can apply this to other database connection settings, such as
our username.

::

	[database]
	host     = "ENC[ENV[PIWIK_DB_HOST]]"
	username = "ENC[ENV[PIWIK_DB_USER]]"
	password = "ENC[ENV[PIWIK_DB_PASSWORD]]"
	dbname   = "ENC[ENV[PIWIK_DB_NAME]]"

How do I set up DBObsecurity?
=============================

Setting an environment variable in Apache
-----------------------------------------

1. Enable the Apache module, mod_env, if necessary.

::

	a2enmod env

2. In our Apache configuration (e.g., httpd.conf or a VirtualHost configuration),
   set the environment variable with our password (e.g., "MyDatabasePassword").

::

	SetEnv PIWIK_DB_PASSWORD MyDatabasePassword

3. Update piwik/config/config.ini.php.

::

	[database]
	host     = "localhost"
	username = "root"
	password = "ENV[PIWIK_DB_PASSWORD]"
	dbname   = "piwik"

Notes:

- in a shared hosting environment, we can set the environment variable in a
  .htaccess file
- our choice of environment variable names may be subject to restrictions in
  php.ini (see safe_mode_allowed_env_vars)
- in config.ini.php, we may have to prefix the environment variable name by
  ``REDIRECT_`` (e.g., ``REDIRECT_PIWIK_DB_PASSWD``) when using php-cgi

Creating a public key/private key pair
--------------------------------------

1. Generate a private key.  (Enter a passphrase when prompted.)

::

	# on older systems
	openssl genrsa -aes256 -out private_key.pem

	# on newer systems
	openssl genpkey -aes-256-cbc -algorithm rsa -out private_key.pem

2. Extract the public key.  (Enter a passphrase when prompted.)

::

	openssl rsa -in private_key.pem -out public_key.pem -pubout

3. Encrypt our password with our private key.  (Enter a passphrase when
   prompted, followed by our user's database password.)

::

	openssl rsautl -inkey private_key.pem -encrypt | openssl enc -a -A

4. Update piwik/config/config.ini.php.

::

	[database]
	host     = "localhost"
	username = "root"
	password = "ENC[JgjvHn4r...N4B2lig=]"
	dbname   = "piwik"

5. Write down our passphrase, print out private_key.pem (or copy it to a USB
   stick), and remove private_key.pem from our server.

Why call it "DBObsecurity"?
===========================
"DBObsecurity" uses the notion of "security by obscurity" to hide our Piwik
database user's plaintext password.  Obscurity is not a widely accepted security
mechanism.  Any increased "depth" it could have provided is likely largely
offset by the fact that the code is open source.

That said, feel free to use this plugin if it'll give you (or your client) some
peace of mind that the password isn't stored in plaintext, in plain sight.
