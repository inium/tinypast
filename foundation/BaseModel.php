<?php
/**
 * PDO Base Model
 *
 * @author inlee <einable@gmail.com>
 */

namespace Foundation;

use \PDO;

class BaseModel
{
    /**
     * PDO Instance
     *
     * @var \PDO
     */
    protected static $pdo = null;

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
        // create pdo instance
        if (is_null(self::$pdo)) {
            // .env에 정의된 데이터를 가져온다.
            // /public/index.php의 Foundation\DotEnv 모듈에서 불러온 값을 사용.
            $conn = (object) array(
                'host' => getenv('DB_HOST'),
                'user' => getenv('DB_USER'),
                'pass' => getenv('DB_PASS'),
                'name' => getenv('DB_NAME'),
                'port' => getenv('DB_PORT') ? getenv('DB_PORT') : 3306,
                'charset' => getenv('DB_CHARSET')
                    ? getenv('DB_CHARSET')
                    : 'utf8'
            );

            $this->createConnection(
                $conn->host,
                $conn->user,
                $conn->pass,
                $conn->name,
                $conn->port,
                $conn->charset
            );
        }

        $this->tableName = $tableName;
    }

    /**
     * PDO static instance를 생성한다.
     *
     * @param string $host      MySQL host
     * @param string $user      MySQL user name
     * @param string $pass      MySQL user password
     * @param string $dbnam     MySQL database name
     * @param integer $port     MySQL Port number. Default is 3306.
     * @param string $charset   MySQL Charset. Default is utf8.
     */
    private function createConnection(
        $host,
        $user,
        $pass,
        $dbname,
        $port = 3306,
        $charset = 'utf8'
    ) {
        try {
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            );

            $dsn = "mysql:host={$host};dbname={$dbname}";
            self::$pdo = new \PDO($dsn, $user, $pass, $options);
            self::$pdo->exec("SET NAMES {$charset}");
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
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

        $stmt = $this->query($query);

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
        $query = "SELECT * FROM {$tableName} WHERE id = ?";

        $stmt = $this->query($query, array($id));

        return $stmt->fetch();
    }

    /**
     * 데이터를 추가한다.
     *
     * @param array $param  Insert 파라미터. 반드시 array는 key => value로 구성.
     * @return integer      마지막에 삽입된 ID
     */
    public function insert($param)
    {
        // Split array keys and values
        $keys = array_keys($param);
        $values = array_values($param);

        $insertColumns = implode(', ', $keys);
        $valueQuestions = implode(
            ', ',
            array_map(function ($value) {
                return '?';
            }, $values)
        );

        $tableName = $this->tableName();
        $query = "INSERT INTO {$tableName} ({$insertColumns})
                         VALUES ({$valueQuestions})";

        $stmt = $this->query($query, $values);
        return self::$pdo->lastInsertId();
    }

    /**
     * ID에 해당하는 row의 Column 값을 Update 한다.
     * -------------------------------------------------------------------------
     * $param = array('calories' => 200, 'colour' => 'green);
     * $id = 10;
     *
     * $object->update($param, $id);
     * -------------------------------------------------------------------------
     * @param array $param  Update 파라미터. 반드시 array는 key => value로 구성.
     * @param integer $id   Update 대상 id
     * @return void
     */
    public function update($param, $id)
    {
        // Split array key and value
        $keys = array_keys($param);
        $values = array_values($param);

        $setClause = implode(
            ', ',
            array_map(function ($key) {
                return "{$key} = ?";
            }, $keys)
        );

        // Query 생성
        $tableName = $this->tableName();
        $query = "UPDATE {$tableName} SET {$setClause} WHERE id = ?";

        // id 추가
        array_push($values, $id);
        $stmt = $this->query($query, $values);

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
        $query = "DELETE FROM {$tableName} WHERE id = ?";

        $stmt = $this->query($query, array($id));

        return $stmt->rowCount();
    }

    /**
     * 질의한 결과를 반환한다.
     * -------------------------------------------------------------------------
     * $query = "SELECT * FROM fruit WHERE calories < ? AND colour = ?";
     * $param = array(150, 'red');
     *
     * $object->query($query, $param);
     * -------------------------------------------------------------------------
     * @param string $query     SQL Query
     * @param array $param      Query Parameters
     * @return \PDOStatement    PDOStatement
     */
    protected function query($query, $param = array())
    {
        try {
            $stmt = self::$pdo->prepare($query);
            $stmt->execute($param);

            return $stmt; // return $stmt->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
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
