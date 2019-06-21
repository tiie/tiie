# Components

Most of the objects are managed using the component mechanism.

There are two types of components:
- services,
- objects.

Services are components initiated once. An example of a service is `@ router`,` @ config`.

One of the usual components is the `resposne` component. Each time you reference a component, a new component instance is created.

The definition of the component is placed in the file. At the time of cancellation, the components' mechanism goes to the repository of the definition and initiates the component.

Below are some examples of component definitions.

```php
# @logger
<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Tiie\Components\Supervisor as Components;

return function(Components $components) {
    $log = new Logger('logs');
    $log->pushHandler(new StreamHandler('../logs/logs.log'));

    return $log;
};
```

Each component definition should be placed in a separate file. The name of the file should be the same as the name of the component.

```php
<?php

use Tiie\Components\Scope;
use Tiie\Components\Supervisor as Components;

use App\Models\Offers\Offers;

return array(
    'init' => function(Components $components, array $params = array()) {
        return new Offers($components->get("@db"));
    },
    'after' => function(Offers $component, Scope $components, array $params = array()) {
        $component->setCategories($components->get('models.offers.categories'));
        $component->form($components->get('models.offers.form'));
        $component->setFiles($components->get('models.offers.files'));
        $component->setOffersTypes($components->get('models.offers.types'));
        $component->setOffersStates($components->get('models.offers.states'));
        $component->setLinksServer($components->get('models.links.server'));
        $component->setUsers($components->get('models.users'));
        $component->setLocations($components->get('models.offers.locations'));

        $component->setEmail($components->get('@mail'));
        $component->setConfig($components->get('@config'));
        $component->setNiceName($components->get('u.nice-name'));
        $component->setLinks($components->get('web.links'));
        $component->setInputs($components->get('@inputs'));
        $component->setValidators($components->get('@validators'));
    },
);
```

In case the component is dependent on other components, and these components are dependent on still other components, it is possible to save the component definitions as objects with the keys `init` and` after`.

`Init` initializes instances of the component. `after` sets other objects for component.

With the help of components it is possible to inject dependence on objects.

## Configuration

| Name            | Type   | Description                                     |
| -               | -      | -                                               |
| components      | object |                                                 |
| components.dirs | list   | List of directories with component definitions. |

Below is a configuration example.

```php
<?php
return array(
	// ...
    "components" => array(
        "dirs" => array(
            "../src/Components"
        )
    ),
	// ...
);
```

## Initialization mechanism

The component mechanism at the moment of component initialization checks first of all the directories defined in the configuration. If the subscriber is not found in the first directory, he will search the next directory.

Generally, if the component is not found in the directories defined in the configuration, it will search the base directory.

Together with the framework, some basic components are defined.

- @cache.php
- @data.encoders.json.php
- @error.handler.php
- @inputs.php
- @lang.dictionaries.tiie.php
- @lang.php
- @log.php
- @logger.php
- @messages.php
- @paths.php
- @performance.php
- @performance.timer.php
- @response.engines.php
- @router.php
- @session.php
- @utils.array.php
- @utils.files.php
- @validators.php
- input.php
- response.php

Each of the above components can be defined at the application level.

Let us assume that we want to modify the `response` component.

The basic configuration of the reponse component looks as follows.

```php
<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components, array $params = array()) {
    $response = new \Tiie\Response\Response($components->get("@config")->get("response"));
    $response->setEngines($components->get("@response.engines"));

    return $response;
};
```

We want to change the `reponse` component to not be the` \Tiie\Response\Response` object, for example `\App\Module\Common\Response` and that the answer is always coded using json.

```php
<?php

use Tiie\Components\Supervisor as Components;

return function(Components $components, array $params = array()) {
    $response = new \App\Module\Common\Response($components->get("@config")->get("response"));
    $response->setEngine('json');

    return $response;
};
```

Definitions are placed in the file `../Components/response.php`.

The simplest way to use a component is to use `\Tiie\Components\ComponentsTrait` which extends the class by the` getComponent` method.

```php
class Offers
{
    use \Tiie\Components\ComponentsTrait;

    private $users;
    private $offers;
    private $validators;
    private $inputs;
    private $response;

    public function get(Request $request)
    {
        $this->response = $this->getComponent("response");

        $offer = $this->findById($request->getParam("id"), $request->getParams(), $request->getFields());
        // ...
    }
}

```