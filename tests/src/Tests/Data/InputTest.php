<?php
namespace Tests\Data;

use Tiie\Data\Input;
use Tests\TestCase;
use Tiie\Validators\ValidatorInterface;
use Tiie\Messages\MessagesInterface;

class InputTest extends TestCase
{
    public function testInput()
    {
        $input = new Input;

        $input->input(array(
            "id" => 10,
            "name" => "Paweł"
        ));


        // $this->createVariable('variable-130', $input->data());
        $this->assertEquals($this->variable('variable-130'), $input->data());

        $input->input(array(
            "age" => 10,
        ));

        // $this->createVariable('variable-131', $input->data());
        $this->assertEquals($this->variable('variable-131'), $input->data());

        $input->input(array(
            "id" => 12,
            "name" => "Paweł"
        ), false);

        // $this->createVariable('variable-132', $input->data());
        $this->assertEquals($this->variable('variable-132'), $input->data());
    }

    /**
     * @throws \Exception
     */
    public function testAllTypes()
    {
        $data = array();

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

        $input->messages(
            new class() implements MessagesInterface {
                public function get(string $code, array $params = array()): ?string
                {
                    return $code;
                }
            }
        );

        $input->messages()
            ->set(ValidatorInterface::ERROR_CODE_NOT_EXISTS, "Brak informacji")
        ;

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
            "@type" => Input::INPUT_DATA_TYPE_OBJECT,
            "id" => array(),
            "name" => array(),
            "age" => array(),
        ));

        $input->prepare();

        $this->assertEquals($this->variable("variable-8"), $input->data());
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
            "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
            "id" => array(),
            "email" => array(),
        ));

        $input->prepare();

        $this->assertEquals($this->variable("variable-9"), $input->data());

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
            "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
            "id" => array(),
            "email" => array(),
            "private" => array()
        ));

        $input->prepare();

        $this->assertEquals($this->variable("variable-10"), $input->data());

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

        $validatorEmail = new \Tiie\Validators\Email;
        $validatorEmail->messages(
            new class() implements MessagesInterface {
                public function get(string $code, array $params = array()): ?string
                {
                    return $code;
                }
            }
        );

        $validatorEmail->message(ValidatorInterface::ERROR_CODE_INVALID, "Invalid data");

        $input->rule("name", array());
        $input->rule("emails", array(
            "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
            "id" => array(),
            "email" => array(
                '@validators' => array(
                    $validatorEmail,
                )
            ),
            "private" => array()
        ));

        $input->prepare();

        $this->assertEquals($this->variable("variable-11"), $input->errors());
    }
}
