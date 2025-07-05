# Webman Resource Plugin

一个为 Webman 框架提供的 API Resource 插件，参照 Laravel 的 API Resource 实现。

## 安装

```bash
composer require viva/webman-resource
```

## 使用方法

### 创建 Resource

```php
<?php

namespace app\resource;

use Viva\WebmanResource\JsonResource;
use support\Request;

class UserResource extends JsonResource
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
        ];
    }
}
```

### 在控制器中使用

```php
<?php

namespace app\controller;

use app\resource\UserResource;
use app\model\User;

class UserController
{
    public function show($id)
    {
        $user = User::find($id);

        return (new UserResource($user))->response();
    }

    public function index()
    {
        $users = User::paginate(10);

        return UserResource::collection($users)->response();
    }
}
```

### 条件加载属性

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->resource->id,
        'name' => $this->resource->name,

        // 条件加载
        'profile' => $this->when($this->resource->relationLoaded('profile'), function () {
            return new ProfileResource($this->resource->profile);
        }),

        // 条件合并
        'meta' => $this->mergeWhen($request->user(), function () {
            return [
                'is_admin' => $this->resource->is_admin,
            ];
        }),
    ];
}
```

### 添加额外数据

```php
return (new UserResource($user))
    ->additional(['meta' => ['version' => '1.0']])
    ->response();
```

### 禁用包装

```php
UserResource::withoutWrapping();

return (new UserResource($user))->response();
```

## 特性

- ✅ 单个资源转换
- ✅ 资源集合
- ✅ 分页资源
- ✅ 条件加载属性
- ✅ 额外数据
- ✅ 包装控制
- ✅ 匿名资源集合

## 许可证

MIT License
