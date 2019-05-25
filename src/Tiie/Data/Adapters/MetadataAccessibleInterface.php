<?php
namespace Tiie\Data\Adapters;

/**
 * The implementing object should be able to return information about the data
 * promises it is working on.
 *
 * On the example of MySQL. The objects are tables, columns. So the
 * implementing adaper should return information about specific tables and
 * columns.
 *
 * @package Tiie\Data\Adapters
 */
interface MetadataAccessibleInterface
{
    /**
     * Return metadata about data objects.
     *
     * ```
     * <?php
     * $adapter->metadata("tables");
     * $adapter->metadata("table", "users");
     * $adapter->metadata("columns");
     * $adapter->metadata("column", "user.id");
     * ```
     * @param string $type
     * @param string|null $id
     * @return mixed
     */
    public function metadata(string $type, string $id = null);
}
