<?php
namespace Elusim\Data\Adapters;

interface MetadataAccessibleInterface
{
    public function metadata($type, $id = null);
}
