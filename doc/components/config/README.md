# Config component

The component is responsible for working with configuration files.

Configuration files are placed in a specific directory. The path to the directory is given when the component is initialized.

Supported configuration formats:

- json,
- yaml
- php

## Working with config

For example, let's assume that we have configuration files in the `./configs` directory.

One of the configuration files is the file `app-config.json`. Which looks as follows

```json
{
    "response" : {
        "headers" :  {
            "Access-Control-Allow-Headers" : "Cache-Control, X-Requested-With, Content-Type"
        },
        "engines" : {
            "default" : "application/json"
        },
        "contentType" : {
            "priorities" : [
                "application/json"
            ]
        },
        "_lang" : {
            "priorities" : [
                "pl-PL,pl",
                "en-US,en"
            ]
        }
    },

    "tiie" : {
        "errors" : {
            "_errorReporting" : [
                "E_PARSE",
                "E_NOTICE",
                "E_CORE_ERROR",
                "E_CORE_WARNING"
            ]
        },
        "errorReportingSilently" : true
    }
}
```

In the first traffic jam, we initiate the component with the path to the directory where the configuration files are located.

```php
$config = new Config(__DIR__ . "/configs");
```

Then we load the configuration file. The component will look for a file in the given directory, it will decode it and save it in the object's memory.

```php
$config->load("app-config.json");
```

After reading the configuration using the `get` method, we can refer to specific keys.

To refer to the keys we use a notation dotted for example `response.engines.default`.

```php
$config->get('response.headers.Access-Control-Allow-Headers'); // "Cache-Control, X-Requested-With, Content-Type"
$config->get('response.engines.default'); // "application/json"
$config->get('response.contentType.priorities.0'); // "application/json"
$config->get('response.lang.priorities.0'); // "pl-PL,pl"
$config->get('response.lang.priorities.2'); // null
$config->get('response.lang.priorities.1'); // "en-US,en"

// Get like array
$config['response.headers.Access-Control-Allow-Headers'];
$config['response.engines.default'];
$config['response.contentType.priorities.0'];
$config['response.lang.priorities.0'];
$config['response.lang.priorities.1'];
$config['response.lang.priorities.2'];
```

### Merge with other config

In general, the configuration consists of many files. In our case, we have two configuration files in the repository.
- `config.json`,
- 'app-config.json`.

In our case the configuration of `config.json` is the basic configuration, and the configuration of the` app-config.json` is an additional one that loads for the `app` environment.

```php
$config = new Config(__DIR__."/configs");

// First we load configuration
$config->load("config.json");

// Then we merge config.json with app-config.json. 
$config->merge("app-config.json");
```

Combining causes the keys from the new configuration to overwrite the keys of the underlying configuration. The joining is recursive.

### Include configuration

Sometimes there is a need to load one configuration within another. For example, in the general configuration, we want to load the configuration into the database.

Loading configuration in a different configuration is possible using the `@include (...)` directive.

Below is an example where a configuration with routing tables for individual groups is made for the general configuration.

```php
<?php
return array(
    // ...
    "router" => array(
        "routes" => array(
            "api" => "@include(router.routes.api)",
            "accounts" => "@include(router.routes.accounts)",
            "cron" => "@include(router.routes.cron)",

            // Page is last route.
            "page" => "@include(router.routes.page)",
        ),
    ),
);
```