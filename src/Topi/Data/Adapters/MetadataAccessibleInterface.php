<?php
namespace Topi\Data\Adapters;

interface MetadataAccessibleInterface
{
    public function metadata($type, $id = null);
}
