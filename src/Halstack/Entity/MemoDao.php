<?php
namespace Halstack\Entity;

use Doctrine\DBAL\Connection;
use Silex\Application;

use Halstack\Entity\Memo;

class MemoDao {

    protected $tableName = "memo";
    protected $model = 'Halstack\Entity\Memo';

    /**
     * Constructor.
     *
     * @param Connection $conn
     * @param Application $app
     */
    public function __construct(Connection $conn, Application $app)
    {
        $this->conn = $conn;
        $this->app = $app;
        //$this->dispatcher = $app['dispatcher'];
    }

    public function find($id)
    {
        $sql = 'SELECT * FROM ' . $this->conn->quoteIdentifier($this->tableName) . ' WHERE id = :id';
        $params = array('id' => $id);
        $stmt = $this->conn->executeQuery($sql, $params);
        $memoCollection = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->model);

        if (empty($memoCollection)) {
            return null;
        }

        return array_shift($memoCollection);
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM ' . $this->conn->quoteIdentifier($this->tableName) . 'ORDER BY created_at DESC';
        $stmt = $this->conn->executeQuery($sql);
        $memoCollection = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->model);
        return $memoCollection;
    }

    public function insert(Memo $memo) {

        $sql = 'INSERT INTO ' . $this->conn->quoteIdentifier($this->tableName) . '
                                    (title, content, created_at, updated_at)
                                    VALUES (:title, :content, :created_at, :updated_at)';

        $now = new \DateTime("now");
        $params = array(
            'title' => $memo->title,
            'content' => $memo->content,
            'created_at' => $now->format('Y-m-d H:i:s'),
            'updated_at' => $now->format('Y-m-d H:i:s')
        );
        $this->conn->executeUpdate($sql, $params);
        $memo->id = $this->conn->lastInsertId();

        return $memo;
    }

/*
    protected function update(Memo $memo) {

        $sql = 'UPDATE ' . $this->conn->quoteIdentifier($this->tableName) . ' SET
        factor_status = :factor_status, status = :status, updated_at = :updated_at WHERE id = :id';

        $related_status = TRAINING::FACTOR_STATUS_POST_FILLED === $factor_status ? Training::STATUS_DONE : $training->status;
        $now = new \DateTime("now");

        $params = array(
            'id' => (int) $training->id,
            'status' => (int) $related_status,
            'factor_status' => (int) $factor_status,
            'updated_at' => $now->format('Y-m-d H:i:s')
        );

        $params_type = array(
            'id' => \PDO::PARAM_INT,
            'status' => \PDO::PARAM_INT,
            'factor_status' => \PDO::PARAM_INT);

        $this->conn->executeUpdate($sql, $params, $params_type);
    }
*/

    public function hydrate(array $row) {

        $memo = new Memo();

        foreach($row as $prop => $value) {
            $memo->$prop = $value;
        }

        return $memo;
    }

}
