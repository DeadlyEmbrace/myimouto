<?php
namespace MyImouto\ExternalStorage;

class Relation implements \IteratorAggregate
{
    protected $driver;
    
    protected $from;
    
    protected $offset;
    
    protected $limit;
    
    protected $order       = [];
    
    protected $where       = [];
    
    protected $whereNot    = [];
    
    protected $greaterThan = [];
    
    protected $lowerThan   = [];
    
    protected $between     = [];
    
    protected $like        = [];
    
    protected $alike       = [];
    
    protected $equalOrLowerThan   = [];
    
    protected $equalOrGreaterThan = [];
    
    protected $page;
    
    protected $loaded  = false;
    
    protected $records = [];
    
    public function __construct(Driver\DriverInterface $driver)
    {
        $this->driver = $driver;
    }
    
    public function getIterator()
    {
        if (!$this->loaded) {
            $this->load();
        }
        return new \ArrayObject($this->records);
    }
    
    public function from($params)
    {
        $this->from = $params;
        return $this;
    }
    
    /**
     * @var array|string $params
     */
    public function order($params)
    {
        $this->order = array_merge($this->order, (array)$params);
        return $this;
    }
    
    public function offset($params)
    {
        $this->offset = $params;
        return $this;
    }
    
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function where(array $params)
    {
        $this->where = array_merge($this->whereNot, $params);
        return $this;
    }
    
    public function whereNot(array $params)
    {
        $this->whereNot = array_merge($this->whereNot, $params);
        return $this;
    }
    
    public function greaterThan(array $params)
    {
        $this->greaterThan = array_merge($this->greaterThan, $params);
        return $this;
    }
    
    public function lowerThan(array $params)
    {
        $this->lowerThan = array_merge($this->lowerThan, $params);
        return $this;
    }
    
    public function equalOrGreaterThan(array $params)
    {
        $this->equalOrGreaterThan = array_merge($this->equalOrGreaterThan, $params);
        return $this;
    }
    
    public function equalOrLowerThan(array $params)
    {
        $this->equalOrLowerThan = array_merge($this->equalOrLowerThan, $params);
        return $this;
    }
    
    public function between(array $params)
    {
        $this->between = array_merge($this->between, $params);
        return $this;
    }
    
    public function like(array $params)
    {
        $this->like = array_merge($this->like, $params);
        return $this;
    }
    
    public function alike(array $params)
    {
        $this->alike = array_merge($this->alike, $params);
        return $this;
    }
    
    public function page($page)
    {
        $this->page = $page;
    }
    
    /**
     * Alias of limit().
     */
    public function perPage($limit)
    {
        $this->limit = $limit;
    }
    
    public function getFrom()
    {
        return $this->from;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getOffset()
    {
        return $this->offset;
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getWhere()
    {
        return $this->where;
    }
    
    public function getWhereNot()
    {
        return $this->whereNot;
    }
    
    public function getGreaterThan()
    {
        return $this->greaterThan;
    }
    
    public function getLowerThan()
    {
        return $this->lowerThan;
    }
    
    public function getEqualOrGreaterThan()
    {
        return $this->equalOrGreaterThan;
    }
    
    public function getEqualOrLowerThan()
    {
        return $this->equalOrLowerThan;
    }
    
    public function getBetween()
    {
        return $this->between;
    }
    
    public function getLike()
    {
        return $this->like;
    }
    
    public function getAlike()
    {
        return $this->alike;
    }
    
    public function getPage()
    {
        return $this->page;
    }
    
    /**
     * Alias of getLimit().
     */
    public function getPerPage()
    {
        return $this->limit;
    }
    
    public function first($limit = 1)
    {
        $this->load();
        if ($this->records) {
            return array_slice($this->records, 0, $limit);
        }
    }
    
    public function take($limit = 1)
    {
        $this->limit($limit);
        $this->load();
        return $this->records;
    }
    
    /**
     * @throw Exception\RuntimeException
     */
    public function paginate($page = null, $perPage = null)
    {
        if ($page) {
            $this->page($page);
        }
        if ($perPage) {
            $this->perPage($perPage);
        }
        if (!$this->page || !$this->limit) {
            throw new Exception\RuntimeException(
                "Both page and perPage must be defined in order to paginate."
            );
        }
        $this->load();
        return $this->records;
    }
    
    public function load()
    {
        if (!$this->loaded) {
            $this->records = $this->driver->execute($this);
            $this->loaded  = true;
        }
        return $this;
    }
    
    public function reset()
    {
        $this->records = [];
        $this->loaded  = false;
        return $this;
    }
}
