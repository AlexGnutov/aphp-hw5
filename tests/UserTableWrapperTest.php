<?php

require_once './src/UserTableWrapper.php';

use PHPUnit\Framework\TestCase;

class UserTableWrapperTest extends TestCase
{
    /**
     * @covers       UserTableWrapper::insert
     * @uses         UserTableWrapper::get
     * @dataProvider insertProvider
     */
    public function testInsert(array $values, array $expected): void
    {
        $dbWrapper = new UserTableWrapper();
        $dbWrapper->insert($values);
        $inserted = $dbWrapper->get()[0];
        $this->assertEquals($expected, $inserted);
    }

    public function insertProvider(): array
    {
        return [
            [
                ['name' => 'Peter', 'surname' => 'Bondra'], ['name' => 'Peter', 'surname' => 'Bondra'],
            ],
        ];
    }

    /**
     * @covers       UserTableWrapper::update
     * @uses         UserTableWrapper::insert
     * @uses         UserTableWrapper::get
     * @dataProvider updateProvider
     */
    public function testUpdate(array $initial, int $id, array $modification, array $expected): void
    {
        $db = new UserTableWrapper();
        $db->insert($initial);
        $db->update($id, $modification);
        $modified = $db->get()[$id];
        $this->assertEquals($expected, $modified);
    }

    public function updateProvider(): array
    {
        return [
            [
                ['name' => 'Martin', 'surname' => 'King'],
                0,
                ['name' => 'Martin', 'surname' => 'Prince'],
                ['name' => 'Martin', 'surname' => 'Prince']
            ],
        ];
    }

    /**
     * @covers       UserTableWrapper::delete
     * @uses         UserTableWrapper::get
     * @uses         UserTableWrapper::insert
     * @dataProvider deleteProvider
     */
    public function testDelete(array $values): void
    {
        $db = new UserTableWrapper();
        $db->insert($values);
        $db->delete(0);
        $rows = $db->get();
        $this->assertEquals(false, isset($rows[0]));
    }

    public function deleteProvider(): array
    {
        return [
            [['name' => 'Peter', 'surname' => 'First']],
        ];
    }

    /**
     * @covers       UserTableWrapper::get
     * @uses         UserTableWrapper::insert
     * @dataProvider getProvider
     */
    public function testGet(array $rows, array $expected): void
    {
        $db = new UserTableWrapper();
        foreach ($rows as $value) {
            $db->insert($value);
        }
        $insertedRows = $db->get();
        $this->assertEquals($expected, $insertedRows);
    }


    public function getProvider(): array
    {
        return
            [
                [
                    [
                        ['name' => 'Peter', 'surname' => 'First'],
                        ['name' => 'Peter', 'surname' => 'Second'],
                        ['name' => 'Peter', 'surname' => 'Third'],
                    ],
                    [
                        ['name' => 'Peter', 'surname' => 'First'],
                        ['name' => 'Peter', 'surname' => 'Second'],
                        ['name' => 'Peter', 'surname' => 'Third'],
                    ],
                ],
            ];
    }

}
