# FairPrice

Dalhousie Libaries Journal Assessment and Consultation Database

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

* [Laravel] (https://laravel.com/docs/5.7) - and its dependencies


### Installing

#### Overview of Steps

1. Load code from Bitbucket to /var/www/<project-name>
2. Convert .env settings to /var/www/connections/<app-name>.inc
3. Create a symbolic link to /var/www/html/<project-name>
```
ln -s /var/www/<project_name>/public /var/www/html/<project_name>
```
4. Deploy script
```	
composer install --no-dev --optimize-autoloader
 
php artisan cache:clear
php artisan route:clear
 
(Migrate creates the Database if it doesn't already exist. This doesn't need to be run if the database is fully configured)
php artisan migrate
```
5. Create new app key. In /var/www/<project-name>
```
php artisan key:generate
```
6. File Permissions

	1. Assumptions:
		* Web server: apache
		* User: <username>
```
chown -R apache:lits /var/www/<project_name>
find /var/www/<project_name> -type f -exec chmod 664 {} \;
find /var/www/<project_name> -type d -exec chmod 775 {} \;
chgrp -R apache storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
```
7. SELinux
```
semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/<project_name>/storage(/.*)?"
semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/<project_name>/bootstrap/cache(/.*)?"
restorecon -Rv "/var/www/<project_name>/storage"
restorecon -Rv "/var/www/<project_name>/bootstrap/cache"
setfacl -R -m u:apache:rwX storage/
setfacl -R -m u:apache:rwX bootstrap/cache/
```
	(Only required for the first installation of Laravel on a server. selinux needs to be told to allow HTTPD to use ldap/ad)
	setsebool -P httpd_can_connect_ldap 1
8. Update <path_to_your_app>/public/.htaccess
```
	Update to be the right value for your app
	<IfModule mod_rewrite.c>
		<IfModule mod_negotiation.c>
			Options -MultiViews
		</IfModule>
	 
		RewriteEngine On
		RewriteBase /<your-app-name>/
	 
		# Redirect Trailing Slashes If Not A Folder...
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)/$ /$1 [L,R=301]   
		 
		# Handle Front Controller...
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteRule ^ index.php [L]
	 
		# Handle Authorization Header
		RewriteCond %{HTTP:Authorization} .
		RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
	</IfModule>
```
9. Update <path_to_your_app>/config/app.php
```
	Update to the right value for your app
	'url' => env('APP_URL', 'https://apps.library.dal.ca/&lt;your-app-name>/'),
```
10. Update <path_to_your_app>/config/session.php
```
	Update variables:
		 /*
		|--------------------------------------------------------------------------
		| Session Cookie Name
		|--------------------------------------------------------------------------
		|
		| Here you may change the name of the cookie used to identify a session
		| instance by ID. The name specified here will get used every time a
		| new session cookie is created by the framework for every driver.
		|DTM cookie' => 'laravel_session'
		*/   
	 
	'cookie' => 'tracker_laravel_session',   
	 
		/*
		|--------------------------------------------------------------------------
		| Session Cookie Path
		|--------------------------------------------------------------------------
		|
		| The session cookie path determines the path for which the cookie will
		| be regarded as available. Typically, this will be the root path of
		| your application but you are free to change this when necessary.
		|DTM path' => '/' ,
		*/   
	 
	'path' => '/software_tracker',
```
11. Run the following commands
```
	php artisan route:clear
	php artisan cache:clear
```
12. Use debug_blacklist
```
	https://github.com/laravel/framework/pull/21336
	'debug_blacklist' => [
		'_ENV' => [
			'APP_KEY',
			'DB_PASSWORD',
			'REDIS_PASSWORD',
			'MAIL_PASSWORD',
			'PUSHER_APP_KEY',
			'PUSHER_APP_SECRET',
			'ADLDAP_CONTROLLERS',
			'ADLDAP_PORT',
			'ADLDAP_BASEDN',
			'ADLDAP_ADMIN_USERNAME',
			'ADLDAP_ADMIN_PASSWORD',
			'ADLDAP_TIMEOUT',
			'ADLDAP_ACCOUNT_SUFFIX',
			'VALENCE_LIBPATH',
			'VALENCE_HOST',
			'VALENCE_POST',
			'VALENCE_SCHEME',
			'VALENCE_APPID',
			'VALENCE_APPKEY',
			'VALENCE_LP_VERSION',
			'VALENCE_USEID',
			'VALENCE_USERKEY',
			'DB_CONNECTION',
			'DB_HOST',
			'DB_PORT',
			'DB_DATABASE',
			'DB_USERNAME',
			'DB_PASSWORD',
			'MAIL_DRIVER',
			'MAIL_HOST',
			'MAIL_PORT',
			'MAIL_USERNAME',
			'MAIL_PASSWORD',
			'MAIL_ENCRYPTION',
			'MAIL_FROM_ADDRESS',
			'MAIL_FROM_NAME',
		],
		'_SERVER' => [
			'APP_KEY',
			'DB_PASSWORD',
			'REDIS_PASSWORD',
			'MAIL_PASSWORD',
			'PUSHER_APP_KEY',
			'PUSHER_APP_SECRET',
			'ADLDAP_CONTROLLERS',
			'ADLDAP_PORT',
			'ADLDAP_BASEDN',
			'ADLDAP_ADMIN_USERNAME',
			'ADLDAP_ADMIN_PASSWORD',
			'ADLDAP_TIMEOUT',
			'ADLDAP_ACCOUNT_SUFFIX',
			'VALENCE_LIBPATH',
			'VALENCE_HOST',
			'VALENCE_POST',
			'VALENCE_SCHEME',
			'VALENCE_APPID',
			'VALENCE_APPKEY',
			'VALENCE_LP_VERSION',
			'VALENCE_USEID',
			'VALENCE_USERKEY',
			'DB_CONNECTION',
			'DB_HOST',
			'DB_PORT',
			'DB_DATABASE',
			'DB_USERNAME',
			'DB_PASSWORD',
			'MAIL_DRIVER',
			'MAIL_HOST',
			'MAIL_PORT',
			'MAIL_USERNAME',
			'MAIL_PASSWORD',
			'MAIL_ENCRYPTION',
			'MAIL_FROM_ADDRESS',
			'MAIL_FROM_NAME',          
		],
		'_POST' => [
			'password',
		],
	],
```

#### Deploy Script - Details

Source: http://rizqi.id/deploy-script-for-laravel-projects

Step 1: Install composer packages. This will update all of your vendor directories to the version used by your developers. Make sure you use composer install, not composer update. Update will pull down the latest versions of packages — not the ones the rest of the team is using (when someone runs update, the packages they’re on are written to composer.lock, and this is what’s used for install — so make sure the lock file is in Git). If a new version is released between the last update and the deployment, it might break the build!
composer install --no-dev --optimize-autoloader

The --no-dev flag excludes packages listed under require-dev. Note that this means tools like phpunit should not be listed under the dev packages if you use this method, because you’ll need it here. Instead, place packages like the IDE Helper or Clockwork in the dev section — you won’t need these in production. Some people have a different deploy script between their production servers and staging servers, so dev packages are used on staging (where tests are run) but excluded on production.

We also optimize composer a bit with --optimize-autoloader. When composer encounters files loaded via PSR-0 or PSR-4, the namespace-to-file-path mappers, it usually does some string manipulation to figure out what file to load. Optimizing composer will scan all the files available, and list them into one big array for easier lookups. Cheap way to cut down on execution time!

Step 2: Optimize Laravel. Pretty simple.
php artisan optimize
php artisan route:cache

Commonly used classes will be put into one file and loaded up at once, instead of having composer hunt through a couple dozen PHP files and load them individually. You can also specify your own classes to be included in this process in the config/compile.php configuration file.

Similarly, route:optimize will compile your application routes so they don’t need to be recalculated on every request. It’s not enabled by default because you’d have to empty the cache every time you add a new route, but it’s free to use on a production server.

Step 3: Clean up old caches. Let’s take out the trash and make sure all stale data is out of the way.
php artisan cache:clear

You definitely don’t want any bits of an old version of your application persisting in the cache, so it should be cleared on every deploy. If you have a command that warms up your cache — filling it up with values instead of waiting for users to fill it on demand — you can run that now, too.

Step 4: Migrate your migrations. You know this drill.
php artisan migrate

An important tip: do not be tempted to use models in migration scripts.

Say you’re moving things around, and you had an old schema with first_name and last_name columns, but want to make a unified name column instead. Don’t be tempted to reach into eloquent and do this:
// do NOT do this in a migration $users = User::all();
$users->each(function($i){
    $i->name = $i->first_name . ' ' . $i->last_name;
    $i->save();
});

It’ll work at the time of writing, but remember — migrations act upon snapshots of the database at the time of writing. The database is defined by prior migrations, which can never change, so it’ll always have the same database state at any given time. But your code — your models and other files — are dynamic and changing. There is no guarantee that, a year later, the User model even has a $name, $first_name or $last_name field. If someone were to deploy your code from scratch, this migration could potentially break it. This is an especially ugly mess to fix later on.

Instead, you can act on the database:
// do THIS instead $users = DB::table('users')->get();
foreach ($users as $user) {
    DB::table('users')->where('id', $user->id)->update(['name', $user->first_name . ' ' . $user->last_name]);
}

This script only relies on the database schema being in its current state, so it’s future-safe.

Step 5: Run tests.

I usually use phpunit, so…
phpunit

Is all there is to it. You can use phpunit’s status code to determine if your application is healthy and good to go — many CI services will detect a failure and call off the build if a command fails.
.htaccess

Copy .htaccess file from public folder to main folder in directory

Copy server.php in main folder to index.php
File Permissions

Source: https://vijayasankarn.wordpress.com/2017/02/04/securely-setting-file-permissions-for-laravel-framework/

Assumptions:
– Web server: apache
– User: <username>

Step 1: chown the root directory:
```
chown -R apache:apache /var/www/html/<laravel name>
```
Step 2: Grant FTP for uploading and working with files (for using any FTP client):
```
usermod -a -G apache <username>
```
Step 3: Set file permission to 644:
```
find /var/www/html/<laravel name> -type f -exec chmod 664 {} \;
```
Step 4: Set directory permission to 755:
```
find /var/www/html/<laravel name> -type d -exec chmod 775 {} \;
```
Step 5: Give rights for web server to read and write storage and cache
```
chgrp -R apache storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
```
In this way your website is up and secure.

####SELinux

Source: (https://stackoverflow.com/questions/30306315/laravel-5-laravel-log-could-not-be-opened-permission-denied)

The folders Storage and Bootstrap/Cache need to have the right SELinux context. This can be achieved via the following commands.
```
semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/html/<Laravel Site>/storage(/.*)?"
semanage fcontext -a -t httpd_sys_rw_content_t "/var/www/html/<Laravel Site>/bootstrap/cache(/.*)?"
```

The SELinux context needs to be applied on the directories.
```
restorecon -Rv "/var/www/html/<Laravel Site>/storage"
restorecon -Rv "/var/www/html/<Laravel Site>/bootstrap/cache"
```

The Apache user needs to have the rights to create files in both directories. This can be achieved via a ACL in CentOS 7.
```
setfacl -R -m u:apache:rwX storage/
setfacl -R -m u:apache:rwX bootstrap/cache/
```



```
npm run production
 
npm run dev
```

Clear view cache
```
php artisan view:clear
```

Rebuild database: THIS ERASES THE EXISTING DATABASE
```
// THIS ERASES THE EXISTING DATABASE
// Create new database migration
php artisan migrate:fresh
```
 
// Seed the database
```
php artisan db:seed
```

Check the name of the file that will be imported in the below command
```
cd /var/www/fairprice/app/Console/Commands
vim import.php
```

// Look for this line
```
Excel::load('live_data.xlsx', function($reader) use ($platforms){
```

Import spreadsheet:
```
php artisan command:import

Run import in background
php artisan command:import &> ~mvail/import_20180202.txt
Ctrl-Z
disown -h %1 // the 1 is for the number is the []
bg
```


## Built With

* [Laravel] (https://laravel.com/) - The web framework used
* [Composer](https://getcomposer.org/) - Dependency Management
* [BootStrap] (https://getbootstrap.com/)


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **[Craig Power](https://github.com/Craig-Power)** - *Initial work*
* **[Margaret Vail](https://github.com/mvail)** - *Updates*
* **Heather MacFadyen** - *Librarian*

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc

