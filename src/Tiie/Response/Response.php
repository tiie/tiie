<?php
namespace Tiie\Response;

use Tiie\Model\RecordInterface;
use Tiie\Model\Records;
use Tiie\Http\Request;
use Tiie\OpenGraph\Preparator as OpenGraphPreparator;

/**
 * Mechanizm do obsługi odpowiedzi.
 */
class Response implements ResponseInterface
{
    use \Tiie\Components\ComponentsTrait;

    const VALUE_SCOPE_CONTENT = "content";
    const VALUE_SCOPE_LAYOUT = "layout";

    /**
     * Code of response. At HTTP protocol is status.
     */
    private $code = 200;

    private $headers = array();

    private $data = array(
        self::VALUE_SCOPE_CONTENT => array(),
        self::VALUE_SCOPE_LAYOUT => array(),
    );

    private $params = array();
    private $engine;
    private $action;

    private $template;
    private $layout;
    private $title;
    private $description;
    private $variables = array();
    private $includes = array();
    private $appends = array();
    private $openGraph;
    private $openGraphInlude = false;

    private $engines;

    function __construct(array $params = array())
    {
        $this->params = $params;

        $this->openGraph = new OpenGraphPreparator();

        if (!empty($this->params["headers"])) {
            $this->headers($this->params["headers"]);
        }
    }

    public function variable(string $name, $value, string $type = "js") : ResponseInterface
    {
        $this->variables[] = array(
            "name" => $name,
            "value" => $value,
            "type" => $type,
        );

        return $this;
    }

    public function title(string $title) : Response
    {
        $this->openGraph->title($title);

        $this->title = $title;

        return $this;
    }

    public function openGraph() : OpenGraphPreparator
    {
        return $this->openGraph;
    }

    public function includeOpenGraph()
    {
        if (!empty($this->title)) $this->openGraph->title($this->title);
        if (!empty($this->description)) $this->openGraph->description($this->description);

        $this->openGraphInlude = true;

        return $this;
    }

    public function description(string $description) : Response
    {
        $this->openGraph->description($description);

        $this->description = $description;

        return $this;
    }

    /**
     * Include some resources like css,js and others files.
     *
     * @param string $path
     * @param string $target
     */
    public function include(string $path, string $target = "head") : Response
    {
        $t = explode(".", $path);

        // Get type from extension.
        $type = $t[count($t) - 1];

        // Same include.
        $this->includes[] = array(
            "path" => $path,
            "target" => $target,
            "type" => $type,
        );

        return $this;
    }

    public function appendTo($target, $text)
    {
        $this->appends[] = array(
            "target" => $target,
            "text" => $text,
        );

        return $this;
    }

    /**
     * Prepare response elements.
     *
     * @param string $section
     * @return string
     */
    public function prepare($section)
    {
        $prepared = '';

        switch ($section) {
        case "html.include":
            $html = '';

            foreach ($this->includes as $include) {
                if ($include["target"] == "head") {
                    switch ($include["type"]) {
                    case "js":
                        $html .= "<script src=\"{$include["path"]}\"></script>\n";
                        break;
                    case "css":
                        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$include["path"]}\">\n";
                        break;
                    }
                }
            }

            return $html;
        case "html.include.body":
            $html = '';

            foreach ($this->includes as $include) {
                if ($include["target"] == "head") {
                    switch ($include["type"]) {
                    case "js":
                        $html .= "<script src=\"{$include["path"]}\"></script>\n";
                        break;
                    case "css":
                        $html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$include["path"]}\">\n";
                        break;
                    }
                }
            }

            return $html;
        case "html.head":
            $html = '';

            if (!empty($this->title)) {
                $html .= "<title>{$this->title}</title>";
            }

            if (!empty($this->description)) {
                $html .= "<meta name=\"description\" content=\"{$this->description}\">";
            }

            // Html include.
            $element = $this->prepare("html.include");

            if (!empty($element)) {
                $html .= "\n{$element}";
            }

            // Html.javascript.variables
            $element = $this->prepare("html.javascript.variables");

            if (!empty($element)) {
                $html .= "\n{$element}";
            }

            if ($this->openGraphInlude) {
                $html .= $this->openGraph->prepare("html");
            }

            return $html;
        case "html.javascript.variables":
            $html = "<script>\n";

            $html = '';
            foreach ($this->variables as $variable) {
                if ($variable["type"] == "js") {
                    $html .= sprintf("    var {$variable["name"]} = %s;\n", json_encode($variable["value"]));
                }
            }

            if (empty($html)) {
                return "";
            }else{
                return "<script>\n{$html}</script>";
            }

            break;
        default:
            break;
        }
    }

    public function template($template = null)
    {
        if (is_null($template)) {
            return $this->template;
        }else{
            $this->template = $template;

            return $this;
        }
    }

    /**
     * Set engines manager.
     *
     * @return $this
     */
    public function engines($engines = null)
    {
        if (is_null($engines)) {
            return $this->engines;
        }else{
            $this->engines = $engines;

            return $this;
        }
    }

    public function layout($layout = null)
    {
        if (is_null($layout)) {
            return $this->layout;
        }else{
            $this->layout = $layout;

            return $this;
        }
    }

    public function action($action = null)
    {
        if (is_null($action)) {
            return $this->action;
        }else{
            $this->action = $action;

            return $this;
        }
    }

    public function engine($engine = null)
    {
        if (is_null($engine)) {
            return $this->engine;
        }else{
            $this->engine = $engine;

            return $this;
        }
    }

    /**
     * Ustawia parametry odpowiedzi. Parameetry są głównie wykorzystywane w
     * momencie tworzenia odpowiedzi. Parametrem może być np. ścieżka do
     * szablonu twiga. Lub layout jaki ma być zastosowany.
     *
     * @param string $name
     * @param string $value
     * @return $this|mixed Jeśli zostanie podane $value zwracany jest $this, w
     * innym przypadku wartość ustawiona pod podaną nazwą.
     */
    public function param($name, $value = null)
    {
        if (is_null($value)) {
            return array_key_exists($name, $this->params) ? $this->params[$name] : null;
        }else{
            $this->params[$name] = $value;

            return $this;
        }
    }

    /**
     * Prepare response.
     *
     * @throws \Exception If response engine is not defined.
     *
     * @return array
     */
    public function response(Request $request)
    {
        $engines = $this->params["engines"];

        // get first engine
        $engine = $engines[array_keys($engines)[0]];

        $accept = $this->accept($request);

        if (!empty($engines[$accept["contentType"]])) {
            $engine = $engines[$accept["contentType"]];
        }else{
            // todo Ostrzerzenie, że nie ma silnika.
        }

        if (!empty($this->engine)) {
            $engine = $this->engine;
        }

        $result = $this->engines->get($engine)->prepare($this, $request, $accept);

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Response\ResponseInterface::header()
     */
    public function header(string $name, $value = null)
    {
        if (is_null($value)) {
            if (array_key_exists($name, $this->headers)) {
                return $this->headers[$name];
            }else{
                return null;
            }
        }else{
            $this->headers[$name] = $value;

            return $this;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Response\ResponseInterface::headers()
     */
    public function headers(array $headers = null)
    {
        if (is_null($headers)) {
            return $this->headers;
        }else{
            $this->headers = $headers;

            return $this;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Response\ResponseInterface::code()
     */
    public function code(string $code = null)
    {
        if (is_null($code)) {
            return $this->code;
        }else{
            $this->code = $code;

            return $this;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Response\ResponseInterface::set()
     */
    public function set(string $name, $value, string $scope = self::VALUE_SCOPE_CONTENT) : ResponseInterface
    {
        $this->data[$scope][$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @see \Tiie\Response\ResponseInterface::get()
     */
    public function get(string $name, string $scope = self::VALUE_SCOPE_CONTENT)
    {
        return array_key_exists($name, $this->data[$scope]) ? $this->data[$scope][$name] : null;
    }

    public function record(RecordInterface $record = null, string $scope = self::VALUE_SCOPE_CONTENT)
    {
        if (is_null($record)) {
            $this->data(null, 0, $scope);
        } else {
            $this->data($record->toArray(), 0, $scope);
        }

        return $this;
    }

    public function records(Records $records, string $scope = self::VALUE_SCOPE_CONTENT)
    {
        $this->data($records->toArray(), 0, $scope);

        return $this;
    }

    /**
     * Pozwala na ustawienie danych do odpowiedzi.
     *
     * @param null|array $data
     * @return null|array|$this
     */
    public function data(array $data = null, int $merge = 1, string $scope = self::VALUE_SCOPE_CONTENT)
    {
        if (func_num_args() == 0) {
            return $this->data[$scope];
        } else {
            if ($merge) {
                if (is_null($data)) {
                    $this->data[$scope] = $data;
                } else {
                    if (is_null($this->data[$scope])) {
                        $this->data[$scope] = $data;
                    } else {
                        $this->data[$scope] = array_merge($this->data[$scope], $data);
                    }
                }
            } else {
                $this->data[$scope] = $data;
            }

            return $this;
        }
    }

    /**
     * @return array
     */
    public function getData(string $scope = self::VALUE_SCOPE_CONTENT): array
    {
        return $this->data[$scope];
    }

    /**
     * @param array $data
     */
    public function setData(array $data, string $scope = self::VALUE_SCOPE_CONTENT): void
    {
        $this->data[$scope] = $data;
    }


    /**
     * Count and return information about accept content type, langue, charset
     * etc.
     */
    private function accept($request)
    {
        $accept = array(
            "lang" => null,
            "contentType" => null,
        );

        // lang
        if (!empty($this->params["lang"]["negotiation"])) {
            $lang = $request->header("Accept-Language");
            $priorities = $this->params["lang"]["priorities"];

            if (is_null($lang)) {
                $lang = $this->params["lang"]["default"];
            }

            $negotiator = new \Negotiation\LanguageNegotiator();
            $accept["lang"] = $negotiator
                ->getBest($lang, $priorities)
                ->getValue()
            ;

        }else{
            $accept["lang"] = $this->params["lang"]["default"];
        }

        if (is_null($accept["lang"])) {
            throw new \Exception("Can not determine Accept-Language. Please define tiie.response.lang.default at config.");
        }

        // content type
        $contentType = null;
        if (!empty($this->params["contentType"]["negotiation"])) {
            // jest wlaczony mechanizm negocjacji
            // pobieram naglowek Accept z zadania
            $contentType = $request->header("Accept");
            $priorities = $this->params["contentType"]["priorities"];

            if (is_null($contentType)) {
                $contentType = $this->params["contentType"]["default"];
            }

            // wykorzystuje zewnetrzna biblioteke do negocjacji
            $negotiator = new \Negotiation\Negotiator();
            $accept["contentType"] = $negotiator
                ->getBest($contentType, $priorities)
                ->getValue()
            ;

        }else{
            $accept["contentType"] = $this->params["contentType"]["default"];
        }

        if (is_null($accept["contentType"])) {
            throw new \Exception("Can not determine Accept. Please define tiie.response.contentType.default at config.");
        }

        return $accept;
    }

    public function counter($number, $page = null, $pageSize = null)
    {
        if (is_array($page)) {
            if (isset($page["page"]) && isset($page["pageSize"])) {
                return $this->counter($number, $page["page"], $page["pageSize"]);
            }
        }

        $this->headers["X-Rows-Number"] = $number;

        if (is_numeric($page) && is_numeric($pageSize)) {
            if ($pageSize > 0 && $page >= 0) {
                $this->headers["X-Rows-Number"] = $number;
                $this->headers["X-Page-Size"] = $pageSize;
                $this->headers["X-Page"] = $page;
                $this->headers["X-Page-Offset"] = $page * $pageSize;
                $this->headers["X-Pages-Number"] = ceil($number / $pageSize);
            }
        }

        return $this;
    }
}
