<?php
namespace Topi\Actions;

// function lang($text, $lang) {
//     return \Topi\func\lang($text, $lang);
// }

class Documentation extends \Topi\Actions\Action
{
    public function action(\Topi\Http\Request $request, array $params = array())
    {
        $router = $this->component('router');

        $analyzer = new \Topi\Documentation\Analyzer($router->actions());
        $data = $analyzer->analyze();

        $docjs = file_get_contents(__DIR__."/resources/doc/doc.js");
        $doccss = file_get_contents(__DIR__."/resources/doc/doc.css");
        $html = file_get_contents(__DIR__."/resources/documentation.html");

        $html = str_replace('${docjs}', $docjs, $html);
        $html = str_replace('${doccss}', $docjs, $html);
        $html = str_replace('${data}', json_encode($data), $html);

        $response = new \Topi\Response();
        $response->data($html);
        $response->contentType('text/html');

        return $response;
    }
}
