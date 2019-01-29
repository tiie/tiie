<?php
namespace Tests\Lang\Dictionaries;

class FilesTest extends \Tests\TestCase
{
    public function testGet()
    {
        $files = new \Tiie\Lang\Dictionaries\Files(sprintf("%s/../App/lang", $this->dir()));

        $this->assertEquals(null, $files->get('pl', 'app.title'));

        $files->create('pl', 'app.title', 'Strona główna');

        $this->assertEquals('Strona główna', $files->get('pl', 'app.title'));
        $files->remove('pl', 'app.title');
    }
}
