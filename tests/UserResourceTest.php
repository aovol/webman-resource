<?php

namespace WebmanResource\Tests;

use WebmanResource\JsonResource;
use support\Request;

class UserResourceTest extends JsonResource
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
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,

            // 条件加载属性
            'profile' => $this->when($this->resource->relationLoaded('profile'), function () {
                return new ProfileResource($this->resource->profile);
            }),

            // 合并值
            'meta' => $this->mergeWhen($request->user(), function () {
                return [
                    'is_admin' => $this->resource->is_admin,
                    'last_login' => $this->resource->last_login,
                ];
            }),
        ];
    }
}
