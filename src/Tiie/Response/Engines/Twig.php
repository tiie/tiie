<?php
namespace Tiie\Response\Engines;

use Tiie\Response\Response;

class Twig implements \Tiie\Response\Engines\EngineInterface
{
    use \Tiie\Components\ComponentsTrait;

    private $config;
    private $params;

    function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function prepare(\Tiie\Response\ResponseInterface $response, \Tiie\Http\Request $request, array $accept)
    {
        $loader = new \Twig_Loader_Filesystem();

        foreach ($this->params["loader"] as $key => $dir) {
            if (is_numeric($key)) {
                $loader->addPath($dir);
            }else{
                $loader->addPath($dir, $key);
            }
        }

        $twig = new \Twig_Environment($loader, array(
            // When set to true, the generated templates have a __toString()
            // method that you can use to display the generated nodes (default
            // to false).
            // debug boolean

            // The charset used by the templates.
            // charset string (defaults to utf-8)

            // The base template class to use for generated templates.
            // base_template_class string (defaults to Twig_Template)

            // An absolute path where to store the compiled templates, or false
            // to disable caching (which is the default).
            // cache string or false

            // When developing with Twig, it's useful to recompile the template
            // whenever the source code changes. If you don't provide a value
            // for the auto_reload option, it will be determined automatically
            // based on the debug value.
            // auto_reload boolean

            // If set to false, Twig will silently ignore invalid variables
            // (variables and or attributes/methods that do not exist) and
            // replace them with a null value. When set to true, Twig throws an
            // exception instead (default to false).
            // strict_variables boolean

            // Sets the default auto-escaping strategy (name, html, js, css,
            // url, html_attr, or a PHP callback that takes the template
            // "filename" and returns the escaping strategy to use -- the
            // callback cannot be a function name to avoid collision with
            // built-in escaping strategies); set it to false to disable
            // auto-escaping. The name escaping strategy determines the
            // escaping strategy to use for a template based on the template
            // filename extension (this strategy does not incur any overhead at
            // runtime as auto-escaping is done at compilation time.)
            // autoescape string

            // A flag that indicates which optimizations to apply (default to
            // -1 -- all optimizations are enabled; set it to 0 to disable).
            // optimizations integer
        ));

        $layout = $response->getLayout();

        if (is_null($layout)) {
            $layout = $this->params['layout'];
        }

        if (!is_null($response->getTemplate())) {
            $rendered = $twig->render($response->getTemplate(), $response->getData(Response::VALUE_SCOPE_CONTENT));

            $dataLayout = $response->getData(Response::VALUE_SCOPE_LAYOUT);

            if (!array_key_exists("head", $dataLayout)) {
                $dataLayout["head"] = $response->prepare("html.head");
            }

            $dataLayout["content"] = $rendered;

            // $body = $twig->render($layout, array(
            //     'head' => $response->prepare("html.head"),
            //     'content' => $rendered,
            // ));

            $body = $twig->render($layout, $dataLayout);

            return array(
                'code' => $response->getCode(),
                'body' => $body,
                'headers' => $response->getHeaders(),
            );
        }else{
            return array(
                'code' => $response->getCode(),
                'body' => "",
                'headers' => $response->getHeaders(),
            );
        }
    }
}
