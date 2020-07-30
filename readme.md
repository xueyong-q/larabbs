## 项目简介

本项目是基于 Laravel 6.x 框架开发的一个 BBS 论坛系统。

## 安装部署

首先将本项目克隆至本地开发目录或服务器部署目录上。

然后安装扩展包：
```sh
$ composer install
```

安装完扩展包后配置项目环境变量，修改 `.env` 配置文件。如不存在 `.env` 配置文件则复制 `.env.example` 文件为 `.env` 并修改配置。

将环境变量配置好后执行数据迁移：
```sh
php artisan migrate
```

然后数据填充【可选】：
```sh
php artisan db:seed
```

测试接口【可选】:
```sh
vendor/bin/phpunit
```

如果是正式环境则执行以下步骤优化其性能。  
生成配置缓存：
```sh
$ php artisan config:cache
```

生成路由缓存：
```sh
$ php artisan route:cache
```

类映射加载优化：
```sh
$ php artisan optimize --force
```

## 依赖扩展

| 扩展依赖名称                 | 说明                   |
| ---------------------------- | ---------------------- |
| overtrue/laravel-lang        | 语言包                 |
| mews/captcha                 | 验证码                 |
| intervention/image           | 图片处理               |
| summerblue/generator         | 代码生成器             |
| barryvdh/laravel-debugbar    | Laravel 调试栏         |
| summerblue/laravel-active    | 为导航栏添加 active 类 |
| mews/purifier                | 内容过滤               |
| guzzlehttp/guzzle            | HTTP 请求套件          |
| overtrue/pinyin              | 中文拼音转换工具       |
| predis/predis                | Redis 扩展             |
| laravel/horizon              | 队列监控扩展           |
| spatie/laravel-permission    | 权限管理               |
| viacreative/sudo-su          | 用户切换工具           |
| summerblue/administrator     | 后台管理               |
| overtrue/easy-sms            | 发送短信               |
| doctrine/dbal                | 数据库抽象层           |
| gregwar/captcha              | 适用 Api 的图片验证码  |
| socialiteproviders/weixin    | 第三方登录扩展         |
| tymon/jwt-auth               | JWT扩展                |
| spatie/laravel-query-builder | 根据 Api 请求构建查询  |
| laravel/passport             | OAuth 认证              |