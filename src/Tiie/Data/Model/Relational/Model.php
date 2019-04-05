<?php
namespace Tiie\Data\Model\Relational;

use Tiie\Data\Model\Record;
use Tiie\Data\Model\Records;
use Tiie\Data\Model\RecordInterface;
use Tiie\Data\Model\ModelInterface;
use Tiie\Data\Model\CreatorInterface;
use Tiie\Data\Model\Creator;
use Tiie\Data\Model\Projection;
use Tiie\Data\Model\Relational\SelectableInterface;
use Tiie\Data\Model\Pagination;
use Tiie\Data\Adapters\Commands\SQL\Delete;
use Tiie\Data\Adapters\Commands\SQL\Expr;
use Tiie\Data\Adapters\Commands\SQL\Insert;
use Tiie\Data\Adapters\Commands\SQL\Select;
use Tiie\Data\Adapters\Commands\SQL\Update;
use Tiie\Data\Adapters\Commands\SQL\Where;
use Tiie\Data\Model\Commands\CreateRecord as CommandCreateRecord;
use Tiie\Data\Model\Commands\RemoveRecord as CommandRemoveRecord;
use Tiie\Data\Model\Commands\SaveRecord as CommandSaveRecord;

use Tiie\Commands\CommandInterface;
use Tiie\Commands\Result\ResultInterface;
use Tiie\Commands\Exceptions\ValidationFailed;

use Tiie\Data\Model\Model as DataModel;

abstract class Model extends DataModel implements SelectableInterface
{
    protected $id = array("id");
    protected $table;

    protected $fields = array(
        "id" => array(
        ),
    );

    function __construct($db)
    {
        $this->db = $db;
    }

    public function fetch(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = null) : array
    {
        return $this->select($params, $fields, $sort, $size, $page)->fetch()->data();
    }

    public function select(array $params = array(), array $fields = array(), array $sort = array(), int $size = null, int $page = null) : Select
    {
        $select = (new Select($this->db))
            ->from($this->table, "base")
            ->columns($this->fields)
            ->sort($sort)
            ->page($page, $size)
        ;

        return $select;
    }

    public function run(CommandInterface $command, array $params = array()) : ?ResultInterface
    {
        die('implementacja');

        if ($command instanceof CommandSaveRecord) {
            return $this->runSaveRecord($command, $params);
        } else if ($command instanceof CommandCreateRecord) {
            return $this->runCreateRecord($command, $params);
        } else if ($command instanceof CommandRemoveRecord) {
            return $this->runRemoveRecord($command, $params);
        } else {
            return parent::run($command);
        }
    }

    protected function runSaveRecord(CommandSaveRecord $command, array $params = array()) : ?ResultInterface
    {
        $this->validateThrow($command, $params);

        $record = $command->record();

        // (new Update($this->db))
        //     ->table("offers")
        //     ->set("title", $record->get("title"))
        //     ->set("description", $record->get("description"))
        //     ->set("reserved", empty($record->get("reserved")) ? 0 : 1)

        //     // Active
        //     ->set("active", $record->get("active"))
        //     ->set("activeFrom", $record->get("activeFrom"))
        //     ->set("activeFromDate", $record->get("activeFromDate"))
        //     ->set("activeToDate", $record->get("activeToDate"))

        //     ->set("modifiedOn", date("Y-m-d H:i:s"))
        //     ->equal("id", $record->id())
        //     ->execute()
        // ;

        return null;
    }

    private function runRemoveRecord(CommandRemoveRecord $command, array $params = array()) : ?ResultInterface
    {
        $this->validateThrow($command, $params);

        $record = $command->record();

        $delete = (new Delete($this->db))
            ->from($this->table)
        ;

        foreach($this->id as $field) {
            $delete->equal($field, $record->get($field));
        }

        $statement->execute();

        return null;
    }

    private function runCreateRecord(CommandCreateRecord $command, array $params = array()) : ?ResultInterface
    {
        $this->validateThrow($command, $params);

        $record = $command->record();

        $insert = (new Insert($this->db));

        foreach ($this->fields as $field) {
            $insert->set($field, $record->get($field));
        }

        $insert->execute();

        $newId = $this->db->lastId();

        // Rest of fields.
        foreach ($form["fields"] as $field) {
            if ($field["baseColumn"]) {
                // Ommit base column.
                continue;
            }

            if (!$input->exists($field["name"])) {
                continue;
            }

            if ($field["multiple"]) {
                // if (is_array($this->prepared[$field["name"]])) {
                if (is_array($input->get($field["name"]))) {
                    foreach ($input->get($field["name"]) as $value) {
                        $this->db->insert(array(
                            "offersData" => array(
                                "offerId" => $offerId,
                                "fieldId" => $field["id"],
                                $field["targetColumn"] => $value,
                            )
                        ));
                    }
                } else {
                    $this->db->insert(array(
                        "offersData" => array(
                            "offerId" => $offerId,
                            "fieldId" => $field["id"],
                            $field["targetColumn"] => $input->get($field["name"]),
                        )
                    ));
                }
            }else{
                $this->db->insert(array(
                    "offersData" => array(
                        "offerId" => $offerId,
                        "fieldId" => $field["id"],
                        $field["targetColumn"] => $input->get($field["name"]),
                    )
                ));
            }
        }

        // Read config
        if (!$input->empty("photos") && is_array($input->get("photos"))) {
            $dir = $this->config->get("api.offers.dir");

            // foreach ($this->prepared["photos"] as $photo) {
            foreach ($input->get("photos") as $i => $photo) {
                // Przenosze pliki do katalogu z oferta
                $file = new File($photo["id"], $this->db);

                $file->move("{$dir}/{$offerId}", "{$offer["nice"]}-{$i}.{$file->extension()}");

                // Łącze pliki z ofertą
                $insert = new Insert($this->db);
                $insert
                    ->table("offersFiles")
                    ->add(array(
                        "offerId" => $offerId,
                        "fileId" => $photo["id"],
                    ))
                    ->execute()
                ;
            }
        }

        return new Result($offerId);
    }

    public function validate(CommandInterface $command, array $params = array()) : ?array
    {
        if ($command instanceof CommandSaveRecord) {
            return $this->validateSaveRecord($command, $params);
        } else if ($command instanceof CommandCreateRecord) {
            return $this->validateCreateRecord($command, $params);
        } else if ($command instanceof CommandRemoveRecord) {
            return $this->validateRemoveRecord($command, $params);
        } else {
            return parent::validate($command, $params);
        }
    }

    private function validateSaveRecord(CommandSaveRecord $command, array $params = array()) : ?array
    {
        return null;
    }

    private function validateCreateRecord(CommandCreateRecord $command, array $params = array()) : ?array
    {
        // First step of validation.
        $input = $this->inputs->create($command->record()->data(), array(
            "categoryId" => array(
                '@filters' => array(
                    "int",
                ),
                '@validators' => array(
                    "exists",
                    "notEmpty",
                ),
            ),
            "activeByPeriod" => array(
                '@validators' => array(
                    "exists",
                    $this->validators->get("NotEmpty")
                        ->message("isEmpty", "Prosimy wypełnić okreś przez który ogłoszenie będzie widoczne."),
                ),
            ),
            "photos" => array(
                "@type" => Input::INPUT_DATA_TYPE_LIST_OF_OBJECTS,
                "@validators" => array(
                    $this->validators->get("MaxNumberOfElements")
                        ->max(10)
                        ->message(ValidatorInterface::ERROR_CODE_EXCEEDS_MAXIMUM_NUMBER_OF_ELEMENTS, "Maksymalna dopuszczalna ilość zdjęć to 10."),
                ),
                "id" => array(),
            ),
            "userId" => array(
                '@filters' => array(
                    "int"
                ),
                '@validators' => array(
                    "notEmpty",
                ),
            ),
        ));

        if (!$input->prepare()) {
            return $input->errors();
        }

        // Rest of data.
        $input->rules($this->form->rules($input->get("categoryId")));

        if (!$input->prepare()) {
            return $input->errors();
        }

        $this->input["create"] = $input;

        return null;
    }

    private function validateRemoveRecord(CommandRemoveRecord $command, array $params = array()) : ?array
    {
        return null;
    }

    private function generateNice(string $title)
    {
        $nice = $this->niceName->generate($title);
        $niceWithSuffix = $nice;
        $i = 0;

        do {
            if ($i) {
                $niceWithSuffix = "{$nice}-{$i}";
            }

            $exists = (new Select($this->db))
                ->from("offers")
                ->equal("nice", $niceWithSuffix)
                ->count()
            ;

            $i++;

        } while($exists);

        return $niceWithSuffix;
    }
}
