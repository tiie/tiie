<?php
namespace Tests\Data;

use Elusim\Data\Input;

class InputTest extends \Tests\TestCase
{
    public function testPrepareData()
    {
        $input = new Input(array(
            'id' => '90898',
            'name' => 'Eva ',
            'lastName' => 'Jan',
            'bank' => array(
                'id' => '102',
                'name' => 'Idea Geting Bank',
            ),
            'emails' => array(
                'vokuwibe-1569@yopmail.com',
                'oniqipuh-0984@yopmail.com',
                'issofussyk-5876@yopmail.com',
            )
        ), array(
            'id' => array(
                '@validators' => array(
                    'notEmpty',
                ),
            ),
            'name' => array(
                '@validators' => array(
                    'notEmpty',
                ),
            ),
            'lastName' => array(
                '@validators' => array(
                    'notEmpty',
                ),
            ),
            'bank' => array(
                '@type' => Input::INPUT_DATA_TYPE_OBJECT,
                'id' => array(
                    '@validators' => array(
                        'notEmpty',
                    ),
                ),
                'name' => array(
                    '@validators' => array(
                        'notEmpty',
                    ),
                ),
            ),
            'emails' => array(
                '@type' => Input::INPUT_DATA_TYPE_VECTOR,
                '@validators' => array(
                    'notEmpty',
                ),
            )
        ));

        $input->prepare();

        // todo [debug] Debug to delete
        die(print_r($input->errors(), true));
        // todo [debug] Debug to delete
        die(print_r($input->prepare(), true));
        // $input->set('lastName', 'kowalski');

        // $input->prepare();

        // $input->all();
        // $input->get();
    }

    public function testSimpleValidate()
    {
        // case
        $input = new Input(array(
            'name' => 'Paweł',
            'age' => 12,
        ));

        $input->rules(array(
            'name' => array(
                '@validators' => array(
                    'notEmpty'
                )
            )
        ));

        $this->assertEquals(null, $input->validate());
        $this->assertEquals(null, $input->get('age'));

        // case
        $input = new Input(array(
            'name' => 'Paweł',
            'age' => 12,
        ));

        $input->rules(array(
            'name' => array(
                '@validators' => array(
                    'notEmpty'
                )
            ),
            'age' => array()
        ));

        $this->assertEquals(null, $input->validate());
        $this->assertEquals(12, $input->get('age'));

        // case
        $input = new Input(array(
            'name' => '',
            'age' => 12,
        ));

        $input->rules(array(
            'name' => array(
                '@validators' => array(
                    'notEmpty'
                )
            )
        ));

        $errors = $input->validate();

        $this->assertEquals('isEmpty', $errors['name'][0]['code']);
    }

    public function testSimpleObject()
    {
        $input = new Input(array(
            'name' => 'Paweł',
            'client' => array(
                'id' => 10,
                'name' => 'Paulina',
                'age' => 12,
                'streetId' => 12,
            )
        ));

        $input->rule("name", array());
        $input->rule("client", array(
            "@type" => "object",
            "id" => array(),
            "name" => array(),
            "age" => array(),
        ));

        $this->assertEquals($this->variable("variable-8"), $input->prepare());
    }

    public function testSimpleList()
    {
        $input = new Input(array(
            'name' => 'Paweł',
            'emails' => array(
                array(
                    'id' => 10,
                    'email' => 'pawel@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 11,
                    'email' => 'kasia@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 12,
                    'email' => 'justyna@o2.pl',
                    'private' => 1,
                ),
            ),
        ));

        $input->rule("name", array());
        $input->rule("emails", array(
            "@type" => "list",
            "id" => array(),
            "email" => array(),
        ));

        $this->assertEquals($this->variable("variable-9"), $input->prepare());

        // case
        $input = new Input(array(
            'name' => 'Paweł',
            'emails' => array(
                array(
                    'id' => 10,
                    'email' => 'pawel@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 11,
                    'email' => 'kasia@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 12,
                    'email' => 'justyna@o2.pl',
                    'private' => 1,
                ),
            ),
        ));

        $input->rule("name", array());
        $input->rule("emails", array(
            "@type" => "list",
            "id" => array(),
            "email" => array(),
            "private" => array()
        ));

        $this->assertEquals($this->variable("variable-10"), $input->prepare());

        // case
        $input = new Input(array(
            'name' => 'Paweł',
            'emails' => array(
                array(
                    'id' => 10,
                    'email' => 'pawel@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 11,
                    'email' => 'kasia@o2.pl',
                    'private' => 1,
                ),
                array(
                    'id' => 12,
                    'email' => 'justyna(doc)o2.pl',
                    'private' => 1,
                ),
            ),
        ));

        $input->rule("name", array());
        $input->rule("emails", array(
            "@type" => "list",
            "id" => array(),
            "email" => array(
                '@validators' => array(
                    new \Elusim\Data\Validators\Email()
                )
            ),
            "private" => array()
        ));

        $this->assertEquals($this->variable("variable-11"), $input->validate());
    }
}
