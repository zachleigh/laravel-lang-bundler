# Laravel Lang Bundler   
[![Build Status](https://travis-ci.org/zachleigh/laravel-lang-bundler.svg?branch=master)](https://travis-ci.org/zachleigh/laravel-lang-bundler)
[![Latest Stable Version](https://poser.pugx.org/zachleigh/laravel-lang-bundler/version.svg)](//packagist.org/packages/zachleigh/laravel-lang-bundler)
[![Total Downloads](https://poser.pugx.org/zachleigh/laravel-lang-bundler/downloads)](https://packagist.org/packages/zachleigh/laravel-lang-bundler)
[![StyleCI](https://styleci.io/repos/72352058/shield?style=flat)](https://styleci.io/repos/72352058)
[![License](https://poser.pugx.org/zachleigh/laravel-lang-bundler/license.svg)](//packagist.org/packages/zachleigh/laravel-lang-bundler)  
##### Make bundles of translation values. 

### Contents
  - [Why](#why)
  - [Install](#install)
  - [Usage](#usage)
  - [Commands](#commands)
  - [Configuration](#configuration)
  - [Limitations](#limitations)
  - [Testing](#testing)
  - [Contributing](#contributing)

### Why
Why use this package? Because this sucks:
```html
<component
    welcome={{ trans('home.welcome') }}
    login={{ trans('home.login') }}
    signup={{ trans('home.signup') }}
    profile={{ trans('user.settings.profile') }}
    friends={{ trans('user.home.friends') }}
    intro={{ trans('user.home.intro') }}
    body={{ trans('user.home.body') }}
></component>
```
And this is so much better:
```html
<component
    :lang={{ transB('bundle_name')->toJson() }}
></component>
```

### Install
##### 1. Install through composer     
```
composer require zachleigh/laravel-lang-bundler
```

##### 2. Register the service provider        
In Laravel's config/app.php file, add the service provider to the array with the 'providers' key.
```
LaravelLangBundler\ServiceProvider::class
```    

##### 3. Publish the config file:
```
php artisan vendor:publish --tag=config
```

##### 4. Create a 'bundles' directory in resources/lang/.      
Do it manually or use the command:
```
php artisan langb:start
```

### Usage
##### 1. Make a bundle.   
Imagine we have two lang files, one called 'home.php' and one called 'user.php'.    

home.php
```php
    'welcome' => 'Welcome!',
    'login' => 'Login',
    'signup' => 'Signup',
```

user.php
```php
    'profile' => 'Your Profile',
    'friends' => 'Your Friends',
    'body' => 'Enter body below',
```
We want all of these values in one bundle.    

Simply register your bundle as an array anywhere in the bundles directory. For example, in the bundles directory, you could create a folder called 'components' and in it a file called 'bundle_name' that looks like this:
```php
return [
    'home.welcome',
    'home.login',
    'home.signup',
    'user.profile',
    'user.friends',
    'user.body'
];
```
Obviously, 'bundle_name' is the name of the bundle. The other values represent keys found in the above lang files.     

Like in other lang folders, any file/folder in the bundles directory is treated as a level in an array. So in the above example, our file path looks like this:    

lang/bundles/components/bundle_name.php       

The path for the 'bundle_name' bundle would be 'bundles.components.bundle_name'. It is also possible to create multiple named bundles within a single file, but this is not recommended because you can not use auto-aliasing for multi-bundle files.

##### 2. Get the bundle using the transB() helper function.     
Get your translated bundle by passing the bundle path to the transB() helper function.
```php
transB('bundles.components.bundle_name');
```
Or use the auto-aliased name:
```php
transB('bundle_name');
```
transB() returns a collection of translated values keyed by the original translation key. Continuing the example above, transB() would give us a collection that contains the following array:
```php
[
    'welcome' => 'Welcome!',
    'login' => 'Login',
    'signup' => 'Signup',
    'profile' => 'Your Profile',
    'friends' => 'Your Friends',
    'body' => 'Enter body below',
];
```

##### 3. Pass parameters to your bundle.     
Like with the standard trans() function, you may pass parameters to the transB() function as the second argument.
```php
transB('bundle_name', ['parameterName' => $value]);
```
If your bundle has conflicting parameter names, you can namespace them. In this example, three values require a `user` parameter.     

user.php translation file:
```php
return [
    'welcome_user' => 'Welcome :user',
    'message_to'   => 'You sent a message to :user',
    'invite_from'  => 'You have an invite from :user'
];
```
Bundle file:
```php
return [
    'user.welcome_user',
    'user.message_to',
    'user.invite_from'
];
```
Avoid the naming conflict by namespacing the parameters when passing them to transB():
```php
transB('bundle_name', [
    'welcome_user.user' => 'Bob',
    'message_to.user' => 'Sally',
    'invite_from.user' => 'George'
]);
```

### Commands
##### php artisan langb:start
Get started by creating a bundles directory in your lang folder.

##### php artisan langb:new {path}
Create a new bundle file located at path. For example:
```
php artisan langb:new components.user.profile
```
This would create the file lang/bundles/components/user/profile.php with an empty returned array.

### Configuration
##### aliases
To shorten the name of bundles, you can register aliases in config.
```php
'aliases' [
    'alias' => 'full.path.to.bundle'
];
```
And then simply use the alias istead of the path in transB():
```php
transB('alias');
```

##### key_transform
If you wish to transform lang file keys to snake_case, StudlyCase, or camelCase, set `key_transform` to 'snake_case', 'studly_case', or 'camel_case'. Default value is 'none'.   
For example, this bundle contains snake cased variables:
```php
return [
    'user.welcome_user',
    'user.message_to',
    'user.invite_from'
];
```
But in your javascript, you want to use came cased variables, set `key_transform` to 'camel_case' to get this bundle:
```php
return [
    'welcomeUser' => 'Welcome user!',
    'messageTo' => 'Message to user',
    'inviteFrom' => 'You have an invitation from user!',
];
```

##### global_key_namespace
If you keep all your translations in a single file, you can set `global_key_namespace` to the name of the file to save yourself some typing. For example, if all your translations are in a file called 'translations.php', you would have to register a bundle like this:
```php
return [
    'bundle_name' => [
        'translations.home',
        'translations.navigation',
        'translations.menu',
        'translations.login'
    ];
];
```
However, if you set `global_key_namespace` to 'translations', you could register it like this:
```php
return [
    'bundle_name' => [
        'home',
        'navigation',
        'menu',
        'login'
    ];
];
```

### Limitations    
This is a brief list of the current issues that need to be resolved to make this package more useful and complete:
  - Currently does not support trans_choice(). Could implement this using parameters and namespacing.
  - Bundle names can now be registered as aliases in config. This is less than ideal though because you have to register a bundle in two places. It would be better to have automatic name resolution or a way to name a bundle within the bundle. Maybe resolve with command that generates aliases automatically and saves them in config?


### Testing
```
composer test
```

### Contributing
Contributions are more than welcome. Fork, improve and make a pull request. For bugs, ideas for improvement or other, please create an [issue](https://github.com/zachleigh/laravel-lang-bundler/issues).
