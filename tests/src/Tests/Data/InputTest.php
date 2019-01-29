<?php
namespace Tests\Data;

use Tiie\Data\Input;

class InputTest extends \Tests\TestCase
{
    public function testAllTypes()
    {
        // const INPUT_DATA_TYPE_VALUE = 'value';
        // const INPUT_DATA_TYPE_OBJECT = 'object';
        // const INPUT_DATA_TYPE_LIST_OF_OBJECTS = 'list-of-objects';
        // const INPUT_DATA_TYPE_LIST = 'vector';

        $data = array();

        // $errors = array(
        //     '@object' => array(
        //         'name' => array(),
        //         'name' => array(),
        //         'name' => array(),
        //     )
        // );

        $input = new Input($data, array(
            'value' => array(
                '@validators' => array(
                    'notEmpty',
                    'exists',
                ),
            ),
            'object' => array(
                '@type' => Input::INPUT_DATA_TYPE_OBJECT,
                '@validators' => array(
                    'exists',
                ),
                'value' => array(
                    '@validators' => array(
                        'notEmpty',
                        'exists',
                    ),
                ),
                'object' => array(
                    '@type' => Input::INPUT_DATA_TYPE_OBJECT,
                    'value' => array(
                        '@validators' => array(
                            'notEmpty',
                            'exists',
                        ),
                    ),
                ),
                'listOfObject' => array(
                    '@type' => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
                    'value' => array(
                        '@validators' => array(
                            'notEmpty',
                            'exists',
                        ),
                    ),
                ),
                'list' => array(
                    '@type' => Input::INPUT_DATA_TYPE_LIST,
                    '@validators' => array(
                        'notEmpty',
                        'exists',
                    ),
                ),
            ),
            'listOfObject' => array(
                '@type' => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
                '@validators' => array(
                    'notEmpty',
                    'exists',
                ),
                'value' => array(
                    '@validators' => array(
                        'notEmpty',
                        'exists',
                    ),
                ),
                'object' => array(
                    '@type' => Input::INPUT_DATA_TYPE_OBJECT,
                    'value' => array(
                        '@validators' => array(
                            'notEmpty',
                            'exists',
                        ),
                    ),
                ),
                'listOfObject' => array(
                    '@type' => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
                    'value' => array(
                        '@validators' => array(
                            'notEmpty',
                            'exists',
                        ),
                    ),
                ),
                'list' => array(
                    '@type' => Input::INPUT_DATA_TYPE_LIST,
                    '@validators' => array(
                        'notEmpty',
                        'exists',
                    ),
                ),
            ),
            'list' => array(
                '@type' => Input::INPUT_DATA_TYPE_LIST,
                '@validators' => array(
                    'notEmpty',
                    'exists',
                ),
            ),
        ));

        $input->prepare();

        // $this->createVariable('variable-110', $input->errors());
        $this->assertEquals($this->variable('variable-110'), $input->errors());

        // -----------------------
        $data['value'] = '';
        $input->input($data);

        $input->prepare();

        // $this->createVariable('variable-111', $input->errors());
        $this->assertEquals($this->variable('variable-111'), $input->errors());

        // -----------------------
        $data['value'] = 'foo';
        $input->input($data);

        $input->prepare();

        // $this->createVariable('variable-112', $input->errors());
        $this->assertEquals($this->variable('variable-112'), $input->errors());

        // -----------------------
        $data['object'] = array();
        $input->input($data);

        $input->prepare();

        // $this->createVariable('variable-113', $input->errors());
        $this->assertEquals($this->variable('variable-113'), $input->errors());

        // -----------------------
        $data['object']['value'] = '';
        $input->input($data);

        $input->prepare();

        // $this->createVariable('variable-114', $input->errors());
        $this->assertEquals($this->variable('variable-114'), $input->errors());
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
                    new \Tiie\Data\Validators\Email()
                )
            ),
            "private" => array()
        ));

        $this->assertEquals($this->variable("variable-11"), $input->validate());
    }
}
