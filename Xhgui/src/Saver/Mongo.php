<?php

namespace Guangzhong\Xhgui\Saver;

use MongoDB\Collection;

class Mongo implements Interfaces
{
    /**
     * @var MongoCollection
     */
    private $_collection;
    /**
     * @var MongoId lastProfilingId
     */
    private static $lastProfilingId;

    public function __construct(Collection $collection)
    {
        $this->_collection = $collection;
    }

    public function save(array $data)
    {
        $data['_id'] = self::getLastProfilingId();

        return $this->_collection->insertOne($data);
    }

    /**
     * Return profiling ID
     * @return MongoId lastProfilingId
     */
    public static function getLastProfilingId()
    {
        if (!self::$lastProfilingId) {
            self::$lastProfilingId = 'xhprof_' . md5(uniqid() . microtime(true));
        }

        return self::$lastProfilingId;
    }
}
