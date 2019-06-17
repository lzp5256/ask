<?php
use think\Route;

// 公共路由分组
Route::group('communal',function (){

});

// 发布路由
Route::group('question',function (){
    Route::post('release','release/question/qr');
});


