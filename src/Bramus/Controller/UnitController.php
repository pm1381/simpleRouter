<?php

namespace Bramus\Controller;

use Bramus\Model\Calculate;
use Bramus\Model\DisplayFormat;
use Bramus\Model\Measure;
use Bramus\Model\Unit;

class UnitController {
    private $calculate;

    public function __construct()
    {
        $this->calculate = new Calculate();
    }

    /**
    * Route("/api/convert/(\d+)") 
    */
    public function convert($amount)
    {
        $from = $_POST['from'];
        $to = $_POST['to'];
        $error = [];        
        $where['name'] = $from;
        $fromResult = $this->makeConvertQuery($where);
        if (count($fromResult) > 0){
            $where['name'] = $to;
            $toResult = $this->makeConvertQuery($where);
            if (count($toResult) > 0 && $fromResult['unit'] == $toResult['unit']) {
                if ($fromResult['unit'] == $toResult['unit']) {
                    $answer = $this->calculate->meterCalculation($amount, $fromResult['value'], $toResult['value']);
                    DisplayFormat::jsonFormat(true, '', $answer);
                } else {
                    $error[]= 'units are not the same';
                }
            } else {
                $error[]= 'second paramter error';
            }
        } else {
            $error[] = 'first parametr unit error';
        }
        if (count($error) > 0) {
            http_response_code(400);
            DisplayFormat::jsonFormat(false, '', [], $error);
        }
    }

    /**
     * Route("/api/addUnit/(\w+)")
     */
    public function addUnit($unitName)
    {
        $unit = new Unit();
        $data = [
            'name' => $unitName
        ];
        $unit->insert($data);
    }

    /**
     * Route("/api/removeUnit/(\w+)")
    */
    public function removeUnit($unitName)
    {
        $unit = new Unit();
        $data = [
            'name' => $unitName
        ];
        $unit->delete($data);
    }

    /**
     * Route("/api/getUnit/(\d+)")
    */
    public function getUnit($unitId)
    {
        if ($unitId < 0) {
            DisplayFormat::jsonFormat(false,'',[], ['unit id must be greater than 0']);
            return;
        }
        $data = [];
        $where = [];
        $unit = new Unit();
        if ($unitId != 0) {
            $where = ['id' => $unitId];
        }
        $result = $unit->where($where)->select();
        foreach($result as $res){
            $data[] = [
                'id' => $res['id'],
                'name' => $res['name']
            ];
        }
        DisplayFormat::jsonFormat(true, '', $data);
    }

    /**
     * Route("/api/updateUnit/(\d+)")
    */
    public function updateUnit($unitId)
    {
        if (isset($_POST['name'])) {
            $newName = $_POST['name'];
            $data = [
                'name' => $newName
            ];
            $unit = new Unit();
            $unit->where(['id' => $unitId])->update($data);
        } else {
            http_response_code(400);
            DisplayFormat::jsonFormat(false,'',[], ['name data does not exist']);
        }
    }

    private function makeConvertQuery($where)
    {
        $measure = new Measure();
        $row = ['name', 'value', 'unit'];
        return $measure->row($row)->where($where)->limit(1)->select();
    }
}