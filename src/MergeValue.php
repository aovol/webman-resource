<?php

namespace WebmanResource;

class MergeValue
{
    /**
     * 要合并的值
     *
     * @var mixed
     */
    public $data;

    /**
     * 创建新的合并值实例
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}
