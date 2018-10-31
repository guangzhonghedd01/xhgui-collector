<?php

namespace Guangzhong\Xhgui\Saver;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;

class Mongo implements Interfaces
{
    /**
     * @var MongoCollection
     */
    private $_collection;

    public function __construct(Collection $collection)
    {
        $this->_collection = $collection;
    }

    public function save(array $data)
    {
        $data['created_at'] = new UTCDateTime();//用于做TTL设置
        return $this->_collection->insertOne($data, ['w' => 0]);
    }
}
