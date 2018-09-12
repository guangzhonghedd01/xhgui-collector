<?php

namespace Guangzhong\Xhgui\Saver;

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

    public function __construct(MongoCollection $collection)
    {
        $this->_collection = $collection;
    }

    public function save(array $data)
    {
        $data['_id'] = self::getLastProfilingId();

        return $this->_collection->insert($data, ['w' => 0]);
    }

    /**
     * Return profiling ID
     * @return MongoId lastProfilingId
     */
    public static function getLastProfilingId()
    {
        if (!self::$lastProfilingId) {
            self::$lastProfilingId = new MongoId();
        }

        return self::$lastProfilingId;
    }
}
