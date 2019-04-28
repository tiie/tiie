<?php
namespace Tests\Data;

use Tiie\Validators\Tinyint;
use Tiie\Validators\ValidatorsManager;
use Tiie\Messages\MessagesInterface;
use Tests\TestCase;

class ValidatorsManagerTest extends TestCase
{
    private function initValidatorsManager()
    {
        return new ValidatorsManager(array(
            array(
                'namespace' => '\\Tiie\\Validators'
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

        $this->assertEquals(true, $validators->get('IsIP') instanceof \Tiie\Validators\IsIP);
        $this->assertEquals(true, $validators->get('Email') instanceof \Tiie\Validators\Email);
    }
}
