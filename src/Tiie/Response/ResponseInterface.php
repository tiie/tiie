<?php
namespace Tiie\Response;

use Tiie\Http\Request;
use Tiie\OpenGraph\Preparator as OpenGraphPreparator;
use Tiie\Model\RecordInterface;
use Tiie\Model\Records;
use Tiie\Response\Engines\Engines;

interface ResponseInterface {
    public function setVariable(string $name, $value, string $type = "js") : void;
    public function setTitle(string $title) : void;
    public function getOpenGraph() : OpenGraphPreparator;
    public function includeOpenGraph();
    public function setDescription(string $description) : Response;
    public function include(string $path, string $target = "head") : Response;
    public function appendTo($target, $text);
    public function prepare($section);
    public function setTemplate(string $template) : void;
    public function getTemplate() : ?string;
    public function setEngines(Engines $engines) : void;
    public function setLayout(string $layout) : void;
    public function getLayout() : ?string;
    public function setAction($action) : void;
    public function getAction();
    public function setEngine($engine = null) : void;
    public function setParam(string $name, $value) : void;
    public function getParam(string $name);
    public function response(Request $request);
    public function setHeader(string $name, $value) : void;
    public function getHeader(string $name) : ?string;
    public function setHeaders(array $headers) : void;
    public function getHeaders() : array;

    /**
     * Set or get code of response.
     *
     * @param string $code
     * @return string|\Tiie\Response\ResponseInterface
     */
    public function setCode(string $code) : void;

    public function getCode();

    /**
     * Set one of value for repose. Value can be used by template engine to render respose.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value, string $scope = self::VALUE_SCOPE_CONTENT) : void;

    /**
     * Return value of given attribute.
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name, string $scope = self::VALUE_SCOPE_CONTENT);
    public function setRecord(RecordInterface $record = null, string $scope = self::VALUE_SCOPE_CONTENT) : void;
    public function setRecords(Records $records, string $scope = self::VALUE_SCOPE_CONTENT) : void;
    public function getData(string $scope = self::VALUE_SCOPE_CONTENT): array;
    public function setData(array $data, string $scope = self::VALUE_SCOPE_CONTENT): void;
    public function getCounter($number, $page = null, $pageSize = null);
}
