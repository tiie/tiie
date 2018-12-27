<?php
namespace Tests\Data;

use Elusim\Data\Validators\Tinyint;
use Elusim\Data\Validators\ValidatorsManager;
use Elusim\Messages\MessagesInterface;
use Tests\TestCase;

class ValidatorsManagerTest extends TestCase
{
    private function initValidatorsManager()
    {
        return new ValidatorsManager(array(
            array(
                'namespace' => '\\Elusim\\Data\\Validators'
            ),
        ), new class implements MessagesInterface {
            public function get(string $code, array $params = array()) : ?string
            {
                if ($code == '@Validators.IsIP.IsEmpty') {
                    return 'Validators.IsIP.IsEmpty';
                } else if($code == '@Validators.Integer.Invalid'){
                    return '@Validators.Integer.Invalid';
                } else {
                    return $code;
                }
            }
        });
    }

    public function testGet()
    {
        $validators = $this->initValidatorsManager();

        $this->assertEquals(true, $validators->get('IsIP') instanceof \Elusim\Data\Validators\IsIP);
        $this->assertEquals(true, $validators->get('Email') instanceof \Elusim\Data\Validators\Email);
    }
}
