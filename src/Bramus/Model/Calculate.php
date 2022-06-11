<?php

namespace Bramus\Model;

class Calculate {
    public function meterCalculation($amount, $from, $to)
    {
        $data = [];
        $data['answer'] = $from * (1 / ($to)) * $amount;
        return $data;
    }
}