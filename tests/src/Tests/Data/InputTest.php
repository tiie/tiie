<?php
namespace Tests\Data;

class InputTest extends \Tests\TestCase
{
    public function testSimpleValidate()
    {
        // case
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
        $input = new \Topi\Data\Input(array(
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
                    new \Topi\Validators\Email()
                )
            ),
            "private" => array()
        ));

        $this->assertEquals($this->variable("variable-11"), $input->validate());
    }
}
