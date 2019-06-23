<?php

/**
 * 将路由名称转换成 CSS 的 class 名称
 *
 * @return void
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}