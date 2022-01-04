# Booting Service

Booting for any PHP applications.

## Table of Contents

- [Getting started](#getting-started)
	- [Requirements](#requirements)
	- [Highlights](#highlights)
- [Documentation](#documentation)
    - [Booter](#booter)
	   - [Create Booter](#create-booter)
       - [Register Boots](#register-boots)
       - [Booting](#booting)
       - [Misc](#misc)
    - [Boots](#boots)
        - [Create Boot](#create-boot)
        - [Dependent Boot](#dependent-boot)
        - [Boot Priority](#boot-priority)
        - [Rebooting](#rebooting)
        - [Boot Info](#boot-info)
- [Credits](#credits)
___

# Getting started

Add the latest version of the booting service project running this command.

```
composer require tobento/service-booting
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Booter

### Create Booter

```php
use Tobento\Service\Booting\Booter;
use Tobento\Service\Booting\BooterInterface;
use Tobento\Service\Booting\AutowiringBootFactory;
use Tobento\Service\Container\Container;

// Any PSR-11 container
$container = new Container();

$booter = new Booter(
    bootFactory: new AutowiringBootFactory($container),
    name: 'app',
    bootMethods: ['register', 'boot'],
    terminateMethods: ['terminate'],
);

var_dump($booter instanceof BooterInterface);
// bool(true)
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **bootFactory** | The boot factory. |
| **name** | The name of the booter. |
| **bootMethods** | The boot methods the booter calls if exists, in the order defined. |
| **terminateMethods** | The terminate methods the booter calls if exists, in the order defined. |

### Register Boots

Each boot class is registered once. If you register the same class again it will just overwrite it.

```php
$booter->register(new ConfigBoot());

// You may set a priority for the boot:
$booter->register(DebugBoot::class, priority: 2000);

$booter->register(
    HttpBoot::class,
    RoutingBoot::class,
);
```

### Booting

```php
// calls the boot methods:
$booter->boot();

// calls the terminate methods
$booter->terminate();
```

You may call the booting methods as many times as you want. By default the boot methods gets called once, except declared otherwise with the constant **REBOOTABLE** in the boot classes. See [Rebooting](#rebooting).

**Example**

```php
$booter->register(ConfigBoot::class);

$booter->register(DebugBoot::class, priority: 2000);

$booter->register(
    HttpBoot::class,
    RoutingBoot::class,
);

$booter->boot();
$booter->terminate();

/*
boot: DebugBoot::class
boot: ConfigBoot::class
boot: HttpBoot::class
boot: RoutingBoot::class
terminate: RoutingBoot::class
terminate: HttpBoot::class
terminate: ConfigBoot::class
terminate: DebugBoot::class
*/
```

### Misc

**get**

Returns the specified boot registry if exist, otherwise NULL.

```php
use Tobento\Service\Booting\BootRegistry;

$boot = $booter->get(MyBoot::class);

var_dump($boot instanceof BootRegistry);
// bool(true)
```

**getBoot**

Returns the specified boot if exist, otherwise NULL.

```php
use Tobento\Service\Booting\BootInterface;

$boot = $booter->getBoot(MyBoot::class);

var_dump($boot instanceof BootInterface);
// bool(true)
```

**getBoots**

Returns the registered boots.

```php
use Tobento\Service\Booting\BootRegistry;

$boots = $booter->getBoots();

foreach($boots as $boot) {
    var_dump($boot instanceof BootRegistry);
    // bool(true)
}
```

**getBooted**

For debugging purposes, you might want to get boots booted.

```php
$booted = $booter->getBooted();
```

## Boots

### Create Boot

You can create a Boot by simply exenting Tobento\Service\Booting\Boot:

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    //
}
```

Currently, your Boot doesn't do anything. Depending on the booter **boot** and **terminate** methods defined, you can now define these methods in your Boot class which support method injection (autowiring).

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    public function boot(): void
    {
        // Do something
    }
    
    public function terminate(): void
    {
        // Do something
    }    
}
```

### Dependent Boot

If your Boot depends on another Boot you may ensure that the Boot has always been initiated before by using the constant **BOOT**.

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    public const BOOT = [
        AnotherBoot::class,
    ];
    
    public function boot(AnotherBoot $boot): void
    {
        // Do something
    }    
}
```

### Boot Priority

You may declare a boot priority by using the constant **PRIORITY**. The default priority is 1000.

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    public const PRIORITY = 2000;
}
```

### Rebooting

By default, when the booter calls the [Booting](#booting) methods muliple times, the boot method gets call once only. You may define methods as rebootable by using the constant **REBOOTABLE**.

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    public const REBOOTABLE = ['terminate'];
}
```

### Boot Info

You may add some info for your boot methods by using the constant **INFO**.

```php
use Tobento\Service\Booting\Boot;

class MyBoot extends Boot
{
    public const INFO = [
        'boot' => 'Some description what the boot method does.',
        'terminate' => 'Some description what the terminate method does.',
    ];
}
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)