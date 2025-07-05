<?php

namespace WebmanResource;

use Countable;
use IteratorAggregate;
use support\Request;
use WebmanResource\Concerns\CollectsResources;

class ResourceCollection extends JsonResource implements Countable, IteratorAggregate
{
    use CollectsResources;

    /**
     * 此资源收集的资源类
     *
     * @var string
     */
    public $collects;

    /**
     * 映射的集合实例
     *
     * @var \support\Collection
     */
    public $collection;

    /**
     * 指示是否应该将所有现有的请求查询参数添加到分页链接
     *
     * @var bool
     */
    protected $preserveAllQueryParameters = false;

    /**
     * 应该添加到分页链接的查询参数
     *
     * @var array|null
     */
    protected $queryParameters;

    /**
     * 创建新的资源实例
     *
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);
    }

    /**
     * 指示应该将所有当前查询参数附加到分页链接
     *
     * @return $this
     */
    public function preserveQuery()
    {
        $this->preserveAllQueryParameters = true;

        return $this;
    }

    /**
     * 指定应该存在于分页链接上的查询字符串参数
     *
     * @param array $query
     * @return $this
     */
    public function withQuery(array $query)
    {
        $this->preserveAllQueryParameters = false;

        $this->queryParameters = $query;

        return $this;
    }

    /**
     * 返回资源集合中的项目数量
     *
     * @return int
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * 将资源转换为 JSON 数组
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item) use ($request) {
            return $item->toArray($request);
        })->all();
    }

    /**
     * 创建表示对象的 HTTP 响应
     *
     * @param Request $request
     * @return \support\Response
     */
    public function toResponse($request)
    {
        // 检查是否是分页器
        if ($this->resource instanceof \support\Paginator) {
            return $this->preparePaginatedResponse($request);
        }

        return parent::toResponse($request);
    }

    /**
     * 创建支持分页的 HTTP 响应
     *
     * @param Request $request
     * @return \support\Response
     */
    protected function preparePaginatedResponse($request)
    {
        if ($this->preserveAllQueryParameters) {
            $this->resource->appends($request->get());
        } elseif (!is_null($this->queryParameters)) {
            $this->resource->appends($this->queryParameters);
        }

        return (new PaginatedResourceResponse($this))->toResponse($request);
    }
}
