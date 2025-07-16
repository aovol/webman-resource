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
            return $resource->map(function ($item) {
                return new $this->collects($item);
            });
        }

        if (is_array($resource)) {
            return (new Collection($resource))->map(function ($item) {
                return new $this->collects($item);
            });
        }

        if ($resource instanceof \support\Paginator) {
            return $resource->getCollection()->map(function ($item) {
                return new $this->collects($item);
            });
        }

        return $resource->map(function ($item) {
            return new $this->collects($item);
        });
    }
}
