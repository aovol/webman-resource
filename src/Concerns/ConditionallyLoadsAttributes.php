<?php

namespace WebmanResource\Concerns;

use support\Request;

trait ConditionallyLoadsAttributes
{
    /**
     * 过滤给定的数据，删除任何为 null 的值
     *
     * @param array $data
     * @return array
     */
    protected function filter($data)
    {
        $index = 0;

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->filter($value);
                $index++;
            } elseif (is_null($value) || (is_string($value) && empty($value))) {
                unset($data[$key]);
            } else {
                $data[$key] = $value;
                $index++;
            }
        }

        return array_values($data) === $data ? array_values($data) : $data;
    }

    /**
     * 当满足给定条件时合并值
     *
     * @param mixed $value
     * @param callable|bool $callback
     * @param callable|bool $default
     * @return MergeValue|MissingValue
     */
    public function mergeWhen($value, $callback, $default = null)
    {
        return $this->when($value, $callback, $default);
    }

    /**
     * 当满足给定条件时合并值
     *
     * @param mixed $value
     * @param callable|bool $callback
     * @param callable|bool $default
     * @return MergeValue|MissingValue
     */
    public function when($value, $callback, $default = null)
    {
        if ($value) {
            return value($callback, $this->resource);
        }

        return func_num_args() === 3 ? value($default) : new MissingValue();
    }

    /**
     * 当不满足给定条件时合并值
     *
     * @param mixed $value
     * @param callable|bool $callback
     * @param callable|bool $default
     * @return MergeValue|MissingValue
     */
    public function unless($value, $callback, $default = null)
    {
        return $this->when(!$value, $callback, $default);
    }
}
