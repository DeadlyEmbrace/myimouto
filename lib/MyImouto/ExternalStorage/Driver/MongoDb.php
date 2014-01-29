<?php
namespace MyImouto\ExternalStorage\Driver;

use MyImouto\ExternalStorage\Relation;

class MongoDb extends AbstractDriver
{
    protected $database;
    
    protected $query = [];
    
    public function __construct(\MongoDB $database)
    {
        $this->database = $database;
    }
    
    public function execute(Relation $relation)
    {
        $this->query = $relation->getWhere();
        
        foreach ($relation->getWhereNot() as $key => $value) {
            $this->addToQuery($key, ['$ne' => $value]);
        }
        foreach ($relation->getGreaterThan() as $key => $value) {
            $this->addToQuery($key, ['$gt' => $value]);
        }
        foreach ($relation->getLowerThan() as $key => $value) {
            $this->addToQuery($key, ['$lt' => $value]);
        }
        foreach ($relation->getEqualOrGreaterThan() as $key => $value) {
            $this->addToQuery($key, ['$gte' => $value]);
        }
        foreach ($relation->getEqualOrLowerThan() as $key => $value) {
            $this->addToQuery($key, ['$lte' => $value]);
        }
        foreach ($relation->getBetween() as $key => $value) {
            $this->addToQuery($key, ['$gte' => $value[0], '$lte' => $value[1]]);
        }
        foreach ($relation->getLike() as $key => $value) {
            $this->query[$key] = new \MongoRegex($value);
        }
        foreach ($relation->getAlike() as $key => $value) {
            $this->query[$key] = ['$not' => new \MongoRegex($value)];
        }
        
        $collection = new \MongoCollection($this->database, $relation->getFrom());
        $cursor     = $collection->find($this->query);
        
        foreach (array_reverse($relation->getOrder()) as $order) {
            $cursor->sort($order);
        }
        
        if ($relation->getPage() && $relation->getLimit()) {
            if ($skip = ($relation->getPage() - 1) * $relation->getLimit()) {
                $cursor->skip($skip);
            }
        } elseif ($relation->getOffset()) {
            $cursor->skip($relation->getOffset());
        }
        if ($relation->getLimit()) {
            $cursor->limit($relation->getLimit());
        }
        
        return iterator_to_array($cursor);
    }
    
    protected function addToQuery($key, array $params)
    {
        if (!isset($this->query[$key])) {
            $this->query[$key] = [];
        }
        $this->query[$key] = array_merge($this->query[$key], $params);
    }
}
