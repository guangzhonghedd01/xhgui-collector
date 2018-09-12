<?php
namespace Guangzhong\Xhgui\Saver;

class File implements Interfaces
{
    private $_file;

    public function __construct($file)
    {
        $this->_file = $file;
    }

    public function save(array $data)
    {
        $json = json_encode($data);
        return file_put_contents($this->_file, $json.PHP_EOL, FILE_APPEND);
    }
}
