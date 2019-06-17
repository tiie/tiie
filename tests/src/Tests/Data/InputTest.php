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

        $input->setInput(array(
            "id" => 10,
            "name" => "Paweł"
        ));


        // $this->createVariable('variable-130', $input->getData());
        $this->assertEquals($this->getVariable('variable-130'), $input->getData());

        $input->setInput(array(
            "age" => 10,
        ));

        // $this->createVariable('variable-131', $input->getData());
        $this->assertEquals($this->getVariable('variable-131'), $input->getData());

        $input->setInput(array(
            "id" => 12,
            "name" => "Paweł"
        ), false);

        // $this->createVariable('variable-132', $input->getData());
        $this->assertEquals($this->getVariable('variable-132'), $input->getData());
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

        $input->setMessages(
            new class() implements MessagesInterface {
                public function get(string $code, array $params = array()): ?string
                {
                    return $code;
                }
            }
        );

        $input->getMessages()
            ->set(ValidatorInterface::ERROR_CODE_NOT_EXISTS, "Brak informacji")
        ;

        $input->prepare();

        // $this->createVariable('variable-110', $input->getErrors());
        $this->assertEquals($this->getVariable('variable-110'), $input->getErrors());

        // -----------------------
        $data['value'] = '';
        $input->setInput($data);

        $input->prepare();

        // $this->createVariable('variable-111', $input->getErrors());
        $this->assertEquals($this->getVariable('variable-111'), $input->getErrors());

        // -----------------------
        $data['value'] = 'foo';
        $input->setInput($data);

        $input->prepare();

        // $this->createVariable('variable-112', $input->getErrors());
        $this->assertEquals($this->getVariable('variable-112'), $input->getErrors());

        // -----------------------
        $data['object'] = array();
        $input->setInput($data);

        $input->prepare();

        // $this->createVariable('variable-113', $input->getErrors());
        $this->assertEquals($this->getVariable('variable-113'), $input->getErrors());

        // -----------------------
        $data['object']['value'] = '';
        $input->setInput($data);

        $input->prepare();

        // $this->createVariable('variable-114', $input->getErrors());
        $this->assertEquals($this->getVariable('variable-114'), $input->getErrors());
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

        $input->setRule("name", array());
        $input->setRule("client", array(
            "@type" => Input::INPUT_DATA_TYPE_OBJECT,
            "id" => array(),
            "name" => array(),
            "age" => array(),
        ));

        $input->prepare();

        $this->assertEquals($this->getVariable("variable-8"), $input->getData());
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

        $input->setRule("name", array());
        $input->setRule("emails", array(
            "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
            "id" => array(),
            "email" => array(),
        ));

        $input->prepare();

        $this->assertEquals($this->getVariable("variable-9"), $input->getData());

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

        $input->setRule("name", array());
        $input->setRule("emails", array(
            "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
            "id" => array(),
            "email" => array(),
            "private" => array()
        ));

        $input->prepare();

        $this->assertEquals($this->getVariable("variable-10"), $input->getData());

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
        $validatorEmail->setMessages(
            new class() implements MessagesInterface {
                public function get(string $code, array $params = array()): ?string
                {
                    return $code;
                }
            }
        );

        $validatorEmail->setMessage(ValidatorInterface::ERROR_CODE_INVALID, "Invalid data");

        $input->setRule("name", array());
        $input->setRule("emails", array(
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

        $this->assertEquals($this->getVariable("variable-11"), $input->getErrors());
    }
}
