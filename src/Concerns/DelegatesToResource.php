<?php

namespace WebmanResource\Concerns;

trait DelegatesToResource
{
    /**
     * 确定给定属性是否存在
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->resource->toArray());
    }

    /**
     * 获取给定偏移量的值
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key): mixed
    {
        return $this->resource->{$key};
    }

    /**
     * 设置给定偏移量的值
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        $this->resource->{$key} = $value;
    }

    /**
     * 取消设置给定偏移量的值
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->resource->{$key});
    }

    /**
     * 确定给定属性是否存在
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->resource->{$key});
    }

    /**
     * 获取给定属性的值
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->resource->{$key};
    }

    /**
     * 设置给定属性的值
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->resource->{$key} = $value;
    }
}
