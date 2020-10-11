<?php
/**
 * PDO Base Model
 *
 * @author inlee <einable@gmail.com>
 */

namespace Foundation\Base;

class Model
{
    /**
     * Database Instance
     *
     * @var \Foundation\Database
     */
    protected $database = null;

    /**
     * 테이블 이름
     *
     * @var string
     */
    private $tableName = null;

    /**
     * 생성자
     *
     * @param string $tableName     테이블 이름
     */
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->database = \Foundation\Database::getInstance();
    }

    /**
     * 테이블의 모든 Row를 반환한다.
     *
     * @return array
     */
    public function findAll()
    {
        $tableName = $this->tableName();
        $query = "SELECT * FROM {$tableName}";

        $stmt = $this->database->query($query);

        return $stmt->fetchAll();
    }

    /**
     * ID에 해당하는 row를 찾아 반환한다.
     *
     * @param integer $id   검색 대상 Row의 ID
     * @return array        검색 결과
     */
    public function findById($id)
    {
        $tableName = $this->tableName();
        $query = "SELECT * FROM {$tableName} WHERE id = :id";

        $stmt = $this->database->query($query, array('id' => $id));

        return $stmt->fetch();
    }

    /**
     * 데이터를 추가한다.
     *
     * @param array $params Insert 파라미터. 반드시 array는 key => value로 구성.
     * @return integer      마지막에 삽입된 ID
     */
    public function insert($params)
    {
        // Split array keys and values
        $columns = implode(', ', array_keys($params));
        $values = implode(
            ', ',
            array_walk($params, function ($v, $k) {
                return ":{$k}";
            })
        );

        $tableName = $this->tableName();
        $query = "INSERT INTO {$tableName} ({$columns}) VALUES ({$values})";

        $stmt = $this->database->query($query, $param);

        return $this->database->pdo()->lastInsertId();
    }

    /**
     * ID에 해당하는 row의 Column 값을 Update 한다.
     * -------------------------------------------------------------------------
     * $param = array('calories' => 200, 'colour' => 'green);
     * $id = 10;
     *
     * $object->update($param, $id);
     * -------------------------------------------------------------------------
     * @param array $params Update 파라미터. 반드시 array는 key => value로 구성.
     * @param integer $id   Update 대상 id
     * @return void
     */
    public function update($params, $id)
    {
        $setClause = implode(
            ', ',
            array_walk($params, function ($v, $k) {
                return "{$k} = :{$k}";
            })
        );

        // Query 생성
        $tableName = $this->tableName();
        $query = "UPDATE {$tableName} SET {$setClause} WHERE id = :id";

        // id 추가
        $params['id'] = $id;
        $stmt = $this->database->query($query, $params);

        return $stmt->rowCount();
    }

    /**
     * ID에 해당하는 Row를 찾아 삭제한다.
     *
     * @param integer $id   검색 대상 Row의 ID
     * @return void
     */
    public function deleteById($id)
    {
        $tableName = $this->tableName();
        $query = "DELETE FROM {$tableName} WHERE id = :id";

        $stmt = $this->database->query($query, array('id' => $id));

        return $stmt->rowCount();
    }

    /**
     * 테이블 이름을 반환한다.
     *
     * @return string
     */
    protected function tableName()
    {
        return $this->tableName;
    }
}
