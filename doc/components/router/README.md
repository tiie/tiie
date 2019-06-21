# Router component

It is a component responsible for directing traffic to relevant actions.

The component is provided as a `@router` service.

```php
$router = $this->getComponent("@router");
```

## Configuration

| Name                                  | Type   | Description                                                       |
| ----                                  | ----   | -----------                                                       |
| router                                | list   |                                                                   |
| router.routes                         | list   | List of groups of routes.                                         |
| router.routes.[].prefix               | string |                                                                   |
| router.routes.[].domain               | string |                                                                   |
| router.routes.[].map                  | list   | List of redirects from addresses to specific actions.             |
| router.routes.[].map.[].urn           | string |                                                                   |
| router.routes.[].map.[].method        | string |                                                                   |
| router.routes.[].map.[].action        | object |                                                                   |
| router.routes.[].map.[].action.class  | string | The name of the class that implements the method to be performed. |
| router.routes.[].map.[].action.method | object | The name of the method to be performed in the class.              |

Example of configuration.

```php
<?php
return array(
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

File `router.routes.api.php`.

```php
<?php
return array(
    "prefix" => "/api",
    "domain" => "cars.com",

    "map" => array(
        "api.offers:get" => array(
            "urn" => "/offers/{i:id}",
            "method" => "get",
            "action" => array(
                "class" => App\Actions\Api\Offers::class,
                "method" => "get"
            )
        ),
        "api.offers:collection" => array(
            "urn" => "/offers",
            "method" => "get",
            "action" => array(
                "class" => App\Actions\Api\Offers::class,
                "method" => "getCollection"
            )
        ),
        "api.offers:post" => array(
            "urn" => "/offers",
            "method" => "post",
            "action" => array(
                "class" => App\Actions\Api\Offers::class,
                "method" => "post"
            )
        ),
        "api.offers.offers:options" => array(
            "urn" => "/offers",
            "method" => "options",
            "action" => array(
                "class" => App\Actions\Api\Options::class,
                "method" => "options"
            )
        ),
        "api.offers.offers:options.for" => array(
            "urn" => "/offers/{i:id}",
            "method" => "options",
            "action" => array(
                "class" => App\Actions\Api\Options::class,
                "method" => "options"
            )
        ),
    )
);
```

Based on the above configuration.
|URL|Action|
|-|-|
|**GET** cars.com/api/offers/100|App\Actions\Api\Offers::get|
|**GET** cars.com/api/offers|App\Actions\Api\Offers::getCollection|
|**POST** cars.com/api/offers|App\Actions\Api\Offers::post|
|**OPTIONS** cars.com/api/offers| App\Actions\Api\Options::options      |
|**OPTIONS** cars.com/api/offers/100| App\Actions\Api\Options::options      |
|**GET** cars.com/api/offers/searches/12| Returns 404|

As you probably noticed, in the configuration you can place placeholders that will turn into parameters. Placeholder is placed using the notation `{name}`, `name` is the name of the parameter under which the value will be available.

The placeholder defined in this way accepts any value. If we want to define what type the value is to be, for example a number, then we use the notation `{type:name}`. When searching for a match, the parameter type will be additionally checked.

Below are list of supported types:

- `{i:name}` - `([0-9]++)`,
- `{a:name}` - `([0-9A-Za-z]++)`,
- `{h:name}` - `([0-9A-Fa-f]++)`,
- `{*:name}` - `(.+?)`,
- `{**:name}` - `(.++)`.

## An example of the implementation of the action

In the previous chapter, an example configuration of the router has been shown. In this chapter, we will look at an example of the method implementation for downloading a specific offer.

```php
<?php
namespace App\Actions\Api;

use Tiie\Data\Input;
use Tiie\Validators\Email;
use Tiie\Http\Request;

use App\Models\Offers\Commands\SendEmail as CommandSendEmail;
use App\Models\Offers\Commands\ActiveOffer as CommandActiveOffer;

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

        $this->response->setRecord($offer);

        return $this->response;
    }

    private function findById(string $id, array $params = array(), array $fields = array())
    {
        return $this->getComponent("models.offers")
            ->getRecord($id, $params, $fields)
        ;
    }
}

```

The first parameter to the method is the `Request` object. It stores information about the request.

In the next line, we create the response object `Response`. The component module participates in this process.

Then, we collect the object of the offerers with the specified ID. The ID is retrieved from the address based on the configuration.

Then, the offer is passed to the `Response` object. The `Response` object is returned by actions.