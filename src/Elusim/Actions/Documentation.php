<?php
namespace Elusim\Actions;

// function lang($text, $lang) {
//     return \Elusim\func\lang($text, $lang);
// }

class Documentation extends \Elusim\Actions\Action
{
    public function action(\Elusim\Http\Request $request, array $params = array())
    {
        $router = $this->component('router');

        $analyzer = new \Elusim\Documentation\Analyzer($router->actions());
        $data = $analyzer->analyze();

        $docjs = file_get_contents(__DIR__."/resources/doc/doc.js");
        $doccss = file_get_contents(__DIR__."/resources/doc/doc.css");
        $html = file_get_contents(__DIR__."/resources/documentation.html");

        $html = str_replace('${docjs}', $docjs, $html);
        $html = str_replace('${doccss}', $docjs, $html);
        $html = str_replace('${data}', json_encode($data), $html);

        $response = new \Elusim\Response();
        $response->data($html);
        $response->contentType('text/html');

        return $response;
    }
}