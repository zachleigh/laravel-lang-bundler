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
  - [Configuration](#configuration)
  - [Limitations](#limitations)
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
There's no command for this yet, so just do it manually.

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

Simply register your bundle as an array anywhere in the bundles directory. For example, in the bundles directory, you could create a file called 'components' that looks like this:
```php
return [
    'bundle_name' => [
        'home.welcome',
        'home.login',
        'home.signup',
        'user.profile',
        'user.friends',
        'user.body'
    ]
];
```
Obviously, 'bundle_name' is the name of the bundle. The other values represent keys found in the above lang files.     

Like in other lang folders, any file/folder in the bundles directory is treated as a level in an array. So in the above example, our file path looks like this:    

lang/bundles/components.php       

In components.php we have the 'bundle_name' bundle. The path for the 'bundle_name' bundle would be 'bundles.components.bundle_name'.

##### 2. Get the bundle using the transB() helper function.     
Get your translated bundle by passing the bundle path to the transB() helper function.
```php
transB('bundles.components.bundle_name');
```
transB() returns a collection of translated values keyed by the original translation key. Continuing the example above, transB() would give us a collection that contains the following array::
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
transB('bundles.components.bundle_name', ['parameterName' => $value]);
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
```html
transB('bundle_name', [
    'welcome_user.user' => 'Bob',
    'message_to.user' => 'Sally',
    'invite_from.user' => 'George'
]);
```

### Configuration
##### shortcuts
To shorten the name of bundles, you can register name shortcuts in config.
```php
'shortcuts' [
    'shortcut_name' => 'full.path.to.bundle'
];
```
And then simply use the shortcut istead of the path in transB():
```php
transB('shortcut_name');
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
  - Bundle names can now be registered as shortcuts in config. This is less than ideal though because you have to register a bundle in two places. It would be better to have automatic name resolution or a way to name a bundle within the bundle.

### Contributing
Contributions are more than welcome. Fork, improve and make a pull request. For bugs, ideas for improvement or other, please create an [issue](https://github.com/zachleigh/laravel-lang-bundler/issues).
