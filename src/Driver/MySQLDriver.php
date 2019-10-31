<?php

namespace Hetg\Framework\Driver;


use Hetg\Framework\Driver\StorageDriverInterface;
use Hetg\Framework\Model\Model;

class MySQLDriver implements StorageDriverInterface
{
    private static $types = [
        'integer' => \PDO::PARAM_INT,
        'int' => \PDO::PARAM_INT,
        'string' => \PDO::PARAM_STR,
        'bool' => \PDO::PARAM_BOOL,
        'boolean' => \PDO::PARAM_BOOL,
        'text' => \PDO::PARAM_STR,
        'datetime' => \PDO::PARAM_STR,
        'date' => \PDO::PARAM_STR,
    ];

    /**
     * @var \PDO
     */
    private $PDO;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var string
     */
    private $primaryField;

    public function __construct(string $dsn, ?string $username, ?string $password, ?array $options, string $class)
    {
        $this->PDO = new \PDO($dsn, $username, $password, $options);
        $this->class = $class;
        $this->table = get_class_vars($class)['table'];
        $this->fields = get_class_vars($this->class)['fields'];
        $this->primaryField = get_class_vars($this->class)['primaryField'];
    }


    /**
     * @param Model $model
     *
     * @throws \Exception
     */
    public function create(Model $model): void
    {
        if (!$model instanceof $this->class){
            throw new \RuntimeException("Model has different class");
        }

        $fieldsDQL = '(' . implode(',',array_keys($this->fields)) . ")";
        $params = [];
        $aliases = [];
        foreach ($this->fields as $field => $type){
            $params[] = [
                'alias' => ':' . $field . '_field',
                'value' => $model->{"get".ucfirst($field)}(),
                'type'  => $type
            ];
            $aliases[$field] = end($params)['alias'];
        }
        $valuesDQL = ' VALUES(' . implode(',',$aliases) . ")";
        $dql = "INSERT INTO " . $this->table . $fieldsDQL . $valuesDQL;

        $this->execute($dql, $params);
    }

    /**
     * @param Model $model
     *
     * @throws \Exception
     */
    public function update(Model $model): void
    {
        if (!$model instanceof $this->class){
            throw new \RuntimeException("Model has different class");
        }

        $params = [];
        $values = [];
        foreach ($this->fields as $field => $type){
            $params[] = [
                'alias' => ':' . $field . '_field',
                'value' => $model->{"get".ucfirst($field)}(),
                'type'  => $type
            ];
            if ($field !== $this->primaryField) {
                $values[$field] = $field . ' = ' . end($params)['alias'];
            }
        }
        $valuesDQL = implode(', ',$values);
        $dql = "UPDATE " . $this->table . " SET " . $valuesDQL . " WHERE " . $this->primaryField . " = :" . $this->primaryField . "_field";

        $this->execute($dql, $params);
    }

    /**
     * @param Model $model
     *
     * @throws \Exception
     */
    public function delete(Model $model): void
    {
        if (!$model instanceof $this->class){
            throw new \RuntimeException("Model has different class");
        }

        $params[] = [
            'alias' => ':' . $this->primaryField . '_field',
            'value' => $model->{"get".ucfirst($this->primaryField)}(),
            'type'  => $this->fields[$this->primaryField]
        ];
        $dql = "DELETE FROM " . $this->table . " WHERE " . $this->primaryField . " = :" . $this->primaryField . "_field";

        $this->execute($dql, $params);
    }

    /**
     * @param array  $where
     *
     * @return Model[]|Model|null
     * @throws \Exception
     */
    public function find(array $where)
    {
        $params = [];
        $queries = [];
        foreach ($where as $param => $value){
            $params[] = [
                'alias' => ':' . $param . '_field',
                'value' => $value,
                'type'  => $this->fields[$param]
            ];
            $queries[$param] = $param . ' = ' . end($params)['alias'];
        }
        $queryDQL = implode(',', $queries);
        $dql = "SELECT * FROM " . $this->table . " WHERE " . $queryDQL;

        return $this->execute($dql, $params);
    }

    /**
     * @param string $dql
     * @param array  $params
     *
     * @return Model[]|Model|null
     * @throws \Exception
     */
    private function execute(string $dql, array $params = [])
    {
        $query = $this->PDO->prepare($dql);

        if (count($params) > 0) {
            foreach ($params as $param) {
                $query->bindParam($param['alias'], $param['value'], self::$types[$param['type']]);
            }
        }

        if (!$query->execute()){
            throw new \RuntimeException($query->errorInfo()[0], $query->errorCode());
        } else {
            $rowCount = $query->rowCount();

            switch ($rowCount){
                case 0:
                    return null;
                case 1:
                    $result = $query->fetch(\PDO::FETCH_ASSOC);
                    if (is_array($result)) {
                        return $this->createModel($result);
                    }
                    return null;
                default:
                    $response = $query->fetchAll(\PDO::FETCH_ASSOC);
                    $result = [];
                    foreach ($response as $item){
                        $result[] = $this->createModel($item);
                    }
                    return $result;
            }
        }
    }

    /**
     * @param array  $item
     *
     * @return Model
     */
    private function createModel(array $item): Model
    {
        $model = new $this->class();

        foreach (array_keys($item) as $field) {
            $model->{"set" . ucfirst($field)}($item[$field]);
        }

        return $model;
    }
}