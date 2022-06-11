<?php

namespace Bramus\Model;

use Bramus\Core\Generate;

class Measure extends Generate
{
    public function __construct()
    {
        $tableName = 'measure';
        parent::__construct($tableName);
    }
}