orange-hotspot
==============

Php command line utilities to check and login to orange public hotspots.
Usefull to maintain connexion.

## Requirements

- PHP CLI v5.3.3 minimum
- `You have to be associated with the hotspot`, theses scripts only cares about the authentification process

## Installation

Installation is a quick process, you just have to install it whith composer : 

``` bash
$ php composer.phar create-project --prefer-dist zeliard91/orange-hotspot ./orange-hostpost dev-master
```

## Configuration

The only configuration variables are the credentials to login to an orange hostpot. (email adress and password)
The install process should have asked you these values.

If you want to modify them, they are in the YAML file app/config/parameters.yml

``` yaml
# app/config/parameters.yml
parameters: 
    login: email@orange.fr
    pass: secret
```

## Usage

All commands are non verbose by default, don't forget to add the -v option to see information messages, they are anyway send to syslog.

### Verification

``` bash
$ php app/console orange:status -v
```

Send request to www.google.com and check if this is really google (Orange hostposts redirects to the authentification page if you are not logged in)


### Check and login

``` bash
$ php app/console orange:check -v
```

If you're not logged in, this command submits your credentials to authenticate.

### Disconnect

``` bash
$ php app/console orange:logout -v
```

Just for fun.


## Maintain connexion

If you want to maintain the connexion, you just have to call regulary the check command by crontab : 

``` crontab
*/5 * * * * /usr/bin/php /fullpath/app/console orange:check
```
