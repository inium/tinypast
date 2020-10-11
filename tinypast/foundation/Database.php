<?php
/**
 * Database Connection
 *
 * @author inlee <einable@gmail.com>
 */
namespace Foundation;

use \PDO;

class Database
{
    /**
     * Database Instance
     *
     * @var \Foundation\Database
     */
    private static $instance = null;

    /**
     * PDO Instance
     *
     * @var \PDO
     */
    private $pdo = null;

    /**
     * 생성자
     *
     * @param string $host      MySQL host
     * @param string $user      MySQL user name
     * @param string $pass      MySQL user password
     * @param string $dbnam     MySQL database name
     * @param integer $port     MySQL Port number. Default is 3306.
     * @param string $charset   MySQL Charset. Default is utf8.
     */
    private function __construct(
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

            $dsn = "mysql:host={$host};dbname={$dbname};port={$port};charset={$charset}";
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
            $this->pdo->exec("SET NAMES {$charset}");
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }

    /**
     * Connection singleton instance를 생성하여 반환한다.
     *
     * @return Foundation\Connection
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
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

            self::$instance = new \Foundation\Database(
                $conn->host,
                $conn->user,
                $conn->pass,
                $conn->name,
                $conn->port,
                $conn->charset
            );
        }

        return self::$instance;
    }

    /**
     * PDO Instance를 반환한다.
     *
     * @return \PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }

    /**
     * 질의한 결과를 반환한다.
     * -------------------------------------------------------------------------
     * $query = "SELECT *
     *           FROM fruit
     *           WHERE calories < :calories AND colour = :colour";
     * $param = array('calories' => 150, 'colour' => 'red');
     *
     * $object->query($query, $param);
     * -------------------------------------------------------------------------
     * @param string $query     SQL Query
     * @param array $param      Query Parameters
     * @return \PDOStatement    PDOStatement
     */
    public function query($query, $param = array())
    {
        try {
            $stmt = $this->pdo->prepare($query);
            foreach ($param as $key => $value) {
                $stmt->bindParam(":{$key}", $value);
            }

            $stmt->execute();

            return $stmt; // return $stmt->fetchAll();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
