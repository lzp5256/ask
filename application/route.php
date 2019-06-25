<?php
use think\Route;

// 公共路由分组
Route::group('communal',function (){

});

// 问题路由
Route::group('question',function (){
    Route::post('release','release/question/qr');
    Route::post('list','question/question/ql');
    Route::post('browse','question/question/qb');
    Route::post('info','question/question/qi');
});


