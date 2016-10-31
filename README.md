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
<vue-component
    welcome={{ trans('home.welcome') }}
    login={{ trans('home.login') }}
    signup={{ trans('home.signup') }}
    profile={{ trans('user.settings.profile') }}
    friends={{ trans('user.home.friends') }}
    intro={{ trans('user.home.intro') }}
    body={{ trans('user.home.body') }}
></vue-component>
```
And this is is so much better:
```html
<vue-component
    :lang={{ transB('bundle_name')->toJson() }}
></vue-component>
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
Simply register your bundle as an array anywhere in the bundles directory. For example, in the bundles directory, you could create a file called 'components' that looks like this:
```php
return [
    'bundle_name' => [
        'home.welcome',
        'home.login',
        'home.signup',
        'user.settings.profile',
        'user.home.friends',
        'user.home.body'
    ]
];
```
Obviously, 'bundle_name' is the name of the bundle. The other values represent keys found in your normal lang files.     

Like in other lang folders, any file/folder in the bundles directory is treated as a level in an array. So in the above example, the path for the 'bundle_name' bundle would be 'bundles.components.bundle_name'.

##### 2. Get the bundle using the transB() helper function.     
Get your translated bundle by passing the bundle path to the transB() helper function.
```php
transB('bundles.components.bundle_name');
```
transB() returns a collection of translated values.

##### 3. Pass parameters to your bundle.     
Like with the standard trans() function, you may pass parameters to the transB() function as the second argument.
```php
transB('bundles.components.bundle_name', ['parameterName' => $value]);
```
Note that parameters will be used for all items found in the bundle, leading to potential naming conflicts. Hopefully this issue will be resolved int he future.

### Configuation
##### shortcuts
To shorten the name of bundles, you can register name shortcuts in config.
```php
'shortcuts' [
    'shortcut_name' => 'full.path.to.bundle'
];
```

##### key_transform
If you wish to transform lang file keys to snake_case, StudlyCase, or camelCase, set `key_transform` to 'snake_case', 'studly_case', or 'camel_case'. Default value is 'none'.   

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
  - Passed parameter names are used for all items in bundle leading to naming conflicts. Need to namespace them: 'key.parameter'
  - Currently does not support trans_choice(). Could also use namespacing here
  - Bundle names can now be registered as shortcuts in config. This is less than ideal though because you have to register a bundle in two places. It would be better to have automatic name resolution or a way to name a bundle within the bundle.

### Contributing
Contributions are more than welcome. Fork, improve and make a pull request. For bugs, ideas for improvement or other, please create an [issue](https://github.com/zachleigh/laravel-lang-bundler/issues).
