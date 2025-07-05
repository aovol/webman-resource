<?php

namespace WebmanResource;

use ArrayAccess;
use JsonSerializable;
use support\Request;
use WebmanResource\Concerns\ConditionallyLoadsAttributes;
use WebmanResource\Concerns\DelegatesToResource;

abstract class JsonResource implements ArrayAccess, JsonSerializable
{
    use ConditionallyLoadsAttributes;
    use DelegatesToResource;

    /**
     * 资源实例
     *
     * @var mixed
     */
    public $resource;

    /**
     * 应该添加到顶级资源数组的额外数据
     *
     * @var array
     */
    public $with = [];

    /**
     * 应该添加到资源响应的额外元数据
     *
     * @var array
     */
    public $additional = [];

    /**
     * 应该应用的"data"包装器
     *
     * @var string|null
     */
    public static $wrap = 'data';

    /**
     * 创建新的资源实例
     *
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * 创建新的资源实例
     *
     * @param mixed ...$parameters
     * @return static
     */
    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }

    /**
     * 创建新的匿名资源集合
     *
     * @param mixed $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        $collection = static::newCollection($resource);

        if (property_exists(static::class, 'preserveKeys')) {
            $collection->preserveKeys = (new static([]))->preserveKeys === true;
        }

        return $collection;
    }

    /**
     * 创建新的资源集合实例
     *
     * @param mixed $resource
     * @return AnonymousResourceCollection
     */
    protected static function newCollection($resource)
    {
        return new AnonymousResourceCollection($resource, static::class);
    }

    /**
     * 将资源解析为数组
     *
     * @param Request|null $request
     * @return array
     */
    public function resolve($request = null)
    {
        $data = $this->toArray($request ?: request());

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        } elseif ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        return $this->filter((array) $data);
    }

    /**
     * 将资源转换为数组
     *
     * @param Request $request
     * @return array
     */
    abstract public function toArray(Request $request): array;

    /**
     * 将模型实例转换为 JSON
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options | JSON_THROW_ON_ERROR);
    }

    /**
     * 获取应该与资源数组一起返回的任何额外数据
     *
     * @param Request $request
     * @return array
     */
    public function with(Request $request)
    {
        return $this->with;
    }

    /**
     * 向资源响应添加额外的元数据
     *
     * @param array $data
     * @return $this
     */
    public function additional(array $data)
    {
        $this->additional = $data;
        return $this;
    }

    /**
     * 获取应该应用于资源响应的 JSON 序列化选项
     *
     * @return int
     */
    public function jsonOptions()
    {
        return 0;
    }

    /**
     * 自定义请求的响应
     *
     * @param Request $request
     * @param \support\Response $response
     * @return void
     */
    public function withResponse(Request $request, $response)
    {
        //
    }

    /**
     * 设置包装器
     *
     * @param string $value
     * @return void
     */
    public static function wrap($value)
    {
        static::$wrap = $value;
    }

    /**
     * 禁用包装
     *
     * @return void
     */
    public static function withoutWrapping()
    {
        static::$wrap = null;
    }

    /**
     * 创建响应
     *
     * @param Request|null $request
     * @return \support\Response
     */
    public function response($request = null)
    {
        return $this->toResponse($request ?: request());
    }

    /**
     * 创建表示对象的 HTTP 响应
     *
     * @param Request $request
     * @return \support\Response
     */
    public function toResponse($request)
    {
        $response = json($this->resolve($request), 200, [], $this->jsonOptions());

        $this->withResponse($request, $response);

        return $response;
    }

    /**
     * 准备资源进行 JSON 序列化
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->resolve(request());
    }

    /**
     * 属性代理，兼容 Laravel Resource
     */
    public function __get($key)
    {
        return $this->resource->{$key};
    }
    public function __isset($key)
    {
        return isset($this->resource->{$key});
    }
}
