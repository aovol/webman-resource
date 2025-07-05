<?php

namespace WebmanResource\Tests;

use WebmanResource\JsonResource;
use support\Request;

class ProfileResourceTest extends JsonResource
{
    /**
     * 将资源转换为数组
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'avatar' => $this->resource->avatar,
            'bio' => $this->resource->bio,
            'location' => $this->resource->location,
            'website' => $this->resource->website,
        ];
    }
}
