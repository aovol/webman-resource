<?php

namespace WebmanResource\Concerns;

use Illuminate\Support\Collection;

trait CollectsResources
{
    /**
     * 收集给定资源
     *
     * @param mixed $resource
     * @return Collection
     */
    protected function collectResource($resource)
    {
        if ($resource instanceof Collection) {
            return $resource;
        }

        if (is_array($resource)) {
            $resource = new Collection($resource);
        }

        if ($resource instanceof \support\Paginator) {
            $resource = $resource->getCollection();
        }

        return $resource->map(function ($item) {
            return new $this->collects($item);
        });
    }
}
