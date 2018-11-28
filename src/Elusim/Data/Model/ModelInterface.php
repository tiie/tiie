<?php
namespace Elusim\Data\Model;

use Elusim\Data\Model\CreatorInterface;

interface ModelInterface
{
    const PROCESS_CREATING = 'creating';
    const PROCESS_SAVING = 'saving';
    const PROCESS_REMOVING = 'removing';

    /**
     * Find and return records.
     *
     * @param array $params
     * @return Records
     */
    public function find(array $params = array()) : Records;

    public function generator(array $params = array()) : iterable;

    /**
     * Fetch data from source.
     *
     * @param array $params
     * @return array
     */
    // public function fetch(array $params = array(), int $onlyId = 0) : array;
    public function fetch(array $params = array(), int $limit = null, int $offset = 0) : array;

    /**
     * Fetch data by id.
     *
     * @param string $id
     * @param array $params
     * @return array|NULL
     */
    public function fetchById(string $id, array $params = array()) : ?array;

    /**
     * Fetch data by ids.
     *
     * @param array $ids
     * @param array $params
     * @return array|NULL
     */
    public function fetchByIds(array $ids, array $params = array()) : ?array;

    /**
     * Run command on given record.
     *
     * @param RecordInterface $record
     * @param string $command
     * @param array $params
     * @return ModelInterface
     */
    public function run(RecordInterface $record, string $command, array $params = array()) : ModelInterface;

    /**
     * Validate given process if can be executed at given record. Method should
     * return null or array with errors.
     */
    public function validate(RecordInterface $record, string $process, array $params = array()) : ?array;

    /**
     * Save record at source.
     *
     * @return \Elusim\Data\Model\ModelInterface
     */
    public function save(RecordInterface $record, array $params = array()) : ModelInterface;

    /**
     * Create record at source. Method shoud return id of new record.
     *
     * @param RecordInterface $record;
     * @return string
     */
    public function create(RecordInterface $record, array $params = array()) : ?string;

    public function creator() : CreatorInterface;

    /**
     * Remove record from source.
     */
    public function remove(RecordInterface $record, array $params = array());

    /**
     * Method shoud create new record with given data.
     */
    public function createRecord(array $data = array()) : RecordInterface;

    /**
     * Return list of records from model.
     */
    public function records(array $ids = array(), array $params = array()) : Records;

    /**
     * Return record with given id.
     */
    public function record(string $id, array $params = array()) : ?RecordInterface;
}
