# RestAPI
For example, we will create a RestAPI to retrieve information about the user's result.

The project has the following structure.

```
├── composer.json
├── configs
│   ├── config.api.development.php
│   ├── config.api.php
│   ├── config.api.production.php
│   ├── development.php
│   ├── general.php
│   └── production.php
├── public
│   ├── index.php
└── src
    ├── Actions
    │   └── Users.php
    └── Components

```

- the `configs` directory contains configuration files,
- the `public` directory contains the file` index.php` which is executed first,
- the `src/App` directory contains the source code,
- the `src/App/Actions` directory contains the actions classes,
- the `src/App/Components` directory contains the definitions of components.

```php
<?php
// public/index.php

require_once "../vendor/autoload.php";

// Run application
$app = new \Tiie\App(array(
    "env" => \Tiie\Env::NAME_DEVELOPMENT,
    "configDir" => "../configs",
));

exit();
```

The `index.php` file is run first. We initiate applications in the file. In the parameters for the `Tiie\App` construct, information about the directory with configuration files and information about the environment in which the application will be transferred are transferred.

The `env` parameter defines in which environment the application is run. For example, the environment determines what configuration files will be started,

The `configDir` parameter indicates the path to the directory with configuration files.

We have three levels of configuration files. The first level is the general level `configs/general.php` it contains the basic configuration. The general configuration is the basis for all subsequent configurations.

The second level of configuration is a configuration depending on the environment in which the application is run. For the `development` interface, this will be the` development.php` file. For the `production` environment this will be the configuration file` production.php`. Configuration for the environment overwrites the general configuration.

The third level of configuration depends on the group that started. Individual actions form groups. For example, you will execute the action `/api/users/` which belongs to the `api` group, then the configuration` config.api.php` will be loaded for this action. If the `/page/register-form` action is loaded and this action is assigned to the` page` group, the `config.page.php` configuration will be loaded.
At the third level, I also have dependence on the launch environment. For example, for the `page` and` development` group, the `page.config.development.php` configuration will start.

In summary, the configuration is based on three levels.
- general level,
- level dependent on the environment variable,
- level depending on the group of the group being read.

The next level overwrites the configuration from the previous level.

For example, for the `development` environment and the` page` group, the configuration will be loaded in the following way.

`general.php -> development.php -> config.page.php -> config.page.development.php`

## Configuration of routing

One of the basic configurations is the routing configuration. The routing configuration contains information about how to process the address.

We can configure the routing at the general configuration level `general.php`, and then modify it in the detailed configuration.

```php
<?php
return array(
    "router" => array(
        "routes" => array(
            // Group api
            "api" => array(
                "prefix" => "/api",
                "map" => array(
                    "api.users.collection" => array(
                        "urn" => "/users",
                        "method" => "get",
                        "action" => array(
                            "class" => \App\Actions\Users::class,
                            "method" => "getCollection"
                        )
                    ),
                    "api.users.get" => array(
                        "urn" => "/users/{id}",
                        "method" => "get",
                        "action" => array(
                            "class" => \App\Actions\Users::class,
                            "method" => "get"
                        )
                    ),
                ),
            ),
        ),
    ),
    "components" => array(
        "dirs" => array(
            "../src/Components"
        ),
    ),
);
```

The configuration of the router is under the `router` index. This is a general configuration. The routing configuration is under the key `router.routes`.

The routing table configuration is under the key `router.routes.{group}`.

For each group, eg `api, prefix` and `map` are defined. `prefix` contains information about the address prefix. `map` is an array indexed after `id` for each redirection from address to share in the system.

Let's now implement the actions of `\ App \ Actions \ Users :: getCollection`.

```php
<?php
namespace App\Actions;

use Tiie\Components\ComponentsTrait;
use Tiie\Http\Request;
use Tiie\Response\ResponseInterface;

class Users {

    use ComponentsTrait;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function getCollection(Request $request) : ResponseInterface {
        $this->response = $this->getComponent("response");

        // ...
        $this->response->setData(array(
            array(
                "id" => 10,
                "name" => "John",
            ),
            // ...
            array(
                "id" => 100,
                "name" => "Mike",
            ),
        ));

        $this->response->setEngine("json");

        return $this->response;
    }
}
```

In the greatest simplification, such an action would look as above. The implementation omitted how the data was downloaded.

Tiie contains tools for communicating with the database and creating a data model. This, however, goes beyond the area of this chapter.

In principle, an action can be an evident method. It is not required that the class inherits from or implements a specific interface.

`use ComponentsTrait` is a` trait` containing helpers to retrieve components.

The 'Request' object is passed to the detector defined as an action. It contains functions by means of which we can easily read information about, for example, this method `HTTP`.

Below is the download of the `response` component. The response is responsible for preparing the response to the customer.

The response object below is returned.

```json
[{"id":10,"name":"John"},{"id":100,"name":"Mike"}]
```



Let's now implement actions to download the user with a specific ID.

```php
<?php
namespace App\Actions;

use Tiie\Components\ComponentsTrait;
use Tiie\Http\Request;
use Tiie\Response\ResponseInterface;

class Users {
    use ComponentsTrait;

    // ...

    public function get(Request $request) : ResponseInterface {
        $this->response = $this->getComponent("response");
        $this->response->setEngine("json");

        $users = array(
            "10" => array(
                "id" => 10,
                "name" => "John",
            ),
            "12" => array(
                "id" => 12,
                "name" => "John",
            ),
        );

        if (empty($users[$request->getParam("id")])) {
            $this->response->setCode(404);

            return $this->response;
        }

        $this->response->setData($users[$request->getParam("id")]);

        return $this->response;
    }
}
```

The scheme is very similar. In the first step, we collect the ID value which was defined in the link - `"urn" => "/users/{id}"`, then check whether the user exists, if not the return code` 404` otherwise the user's data is returned .
