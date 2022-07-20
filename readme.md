<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

### CURRENT CHANGES / THOUGHTS ON HOW TO DO IT.
```text
when viewing the image list of community added images. Use the edit button to go to the EXISTING deficiency page
BUT... highlight the image that is not yet approved and allow for that image to be downloaded!!!!  
so it can be edited and re-uploaded


steps
> change the function to have a flag for "include community images on deficiency"
> put highlight on images that are not yet approved
> add download button to that image
> usual remove and then add new / edited image

```
### CONNECT TO SERVER WITH 
```text
 ssh -i /Users/climbican/digitalOcean_mikes_mac root@64.227.103.23 nutrient.tech
 ssh -i /Users/climbican/digitalOcean_mikes_mac root@192.241.216.17 nutrienttechnologies.com
 ```


### wordpress permissions
````text
chown www-data:www-data  -R * # Let Apache be owner
find . -type d -exec chmod 755 {} \;  # Change directory permissions rwxr-xr-x
find . -type f -exec chmod 644 {} \;  # Change file permissions rw-r--r--
````


## I made a custom change on 
Illuminate\Database\Query\Builder.php to accept another param because the original was causing an error in mysql
### may not be necessary, it worked properly after upgrade from 5.2 -> 5.8
```php
 public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null, $table_to_count_from=null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
		// MY CHANGE!!! I CHANGED THE CODE BECAUSE IT CAUSES AN ERROR IN MYSQL ON MULTIPLE COLUMNS
	    $total = (is_null($table_to_count_from)) ? $this->getCountForPagination($columns) : $this->getCountForPagination([$table_to_count_from.'.id']);

        $results = $total ? $this->forPage($page, $perPage)->get($columns) : [];

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
```
###Setup for pulling git hub repository
```text
Github >> Settings (right side of screen) >>> SSH & GPG Keys
add new key from server see below


--> check version first to see if it's installed
git --version
--> if not install 
apt install git
--> add key from server to github??? 
--- instructions from github https://help.github.com/en/github/authenticating-to-github/error-permission-denied-publickey
 
--> ssh-keygen
--- This will start the process of generating key
--- copy the contents 
vi id_rsa.pub
```

###Update server from GIT
```text
git pull git@github.com:climbican/fitnessfeed_serv.git
---> password for id_rsa.pub key --->d1g3M()pSlut<---

```

##.ENV.PROD JUST IN CASE BACKUP FROM SERVER ON JUNE 29, 2022    
```text 
APP_URL=https://app.nutrient.tech

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nutritech
DB_USERNAME=ledge90G@lcl
DB_PASSWORD=Pl2SBgH2WG4gwsRB

PHPMyAdmim credentials
$dbuser='phpmyadmin';
$dbpass='d0nt@sk2ice';
```
