<?php
namespace Tiie\Data\Adapters;

interface MetadataAccessibleInterface
{
    public function metadata($type, $id = null);
}
