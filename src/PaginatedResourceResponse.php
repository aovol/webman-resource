<?php

namespace WebmanResource;

use support\Request;

class PaginatedResourceResponse
{
    /**
     * 资源集合
     *
     * @var ResourceCollection
     */
    protected $resource;

    /**
     * 创建新的分页资源响应实例
     *
     * @param ResourceCollection $resource
     */
    public function __construct(ResourceCollection $resource)
    {
        $this->resource = $resource;
    }

    /**
     * 创建响应
     *
     * @param Request $request
     * @return \support\Response
     */
    public function toResponse($request)
    {
        $paginator = $this->resource->resource;

        $data = [
            'data' => $this->resource->resolve($request),
            'links' => $this->paginationLinks($paginator),
            'meta' => $this->meta($paginator),
        ];

        return json($data);
    }

    /**
     * 获取分页链接
     *
     * @param \support\Paginator $paginator
     * @return array
     */
    protected function paginationLinks($paginator)
    {
        return [
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ];
    }

    /**
     * 获取分页元数据
     *
     * @param \support\Paginator $paginator
     * @return array
     */
    protected function meta($paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ];
    }
}
