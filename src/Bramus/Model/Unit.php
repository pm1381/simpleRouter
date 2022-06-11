<?php

namespace Bramus\Model;

use Bramus\Core\Generate;

class Unit extends Generate
{
    public function __construct()
    {
        $tableName = 'unit';
        parent::__construct($tableName);
    }
}