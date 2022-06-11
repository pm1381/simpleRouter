<?php

use Bramus\Model\Measure;
use Bramus\Model\Unit;
use PHPUnit\Framework\TestCase;

class GenerateTest extends TestCase
{

    protected function setUp(): void {}
    protected function tearDown(): void {}

    public function testUnitSelect()
    {
        $unit = new Unit();
        $unit->row(['id', 'name'])->where([])->select();
        $query = $unit->getQuery();
        $this->assertSame($query, 'SELECT `id`, `name` FROM `unit`');
    }

    public function testUnitUpdate()
    {
        $unit = new Unit();
        $data = [
            'name' => 'update name'
        ];
        $unit->where(['id' => 0])->update($data);
        $query = $unit->getQuery();
        $this->assertSame($query, "UPDATE `unit` SET name=:name WHERE (id=:id)");
    }

    public function testUnitInsert()
    {
        $unit = new Unit();
        $data = [
            'name' => 'new Name'
        ];
        $unit->insert($data);
        $query = $unit->getQuery();
        $this->assertSame($query, 'INSERT INTO `unit`(`name`) VALUES (:name)');
    }

    public function testUnitDelete()
    {
        $unit = new Unit();
        $unit->delete(['id' => 5]);
        $query = $unit->getQuery();
        $this->assertSame($query, 'DELETE FROM `unit` WHERE (id=:id)');
    }

    // also you can check CRUDs for measure model

}