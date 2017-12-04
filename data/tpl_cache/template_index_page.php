<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/page|template/index/header', '1511612567', 'template/index/page');?><!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>任务帮</title>

        <link rel="stylesheet" type="text/css" href="/mat/dist/css/index/iconfont/iconfont.css">
        <!-- Custom styles for this template -->
        <link href="/mat??/dist/css/index/style.css,dist/css/admin/pop_css.css" rel="stylesheet" type="text/css">
</head>


<body>
<!-- 头部 -->
<div class="header">
        <div class="hed_lf"><a href="javascript:;"  onclick="javascript:window.history.back(-1);"><i class="icon iconfont icon-sanjiao"></i></a></div>
        <div class="hed_ct"><?=$str_page_title?></div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
        <div class="announ_cement">
                <div class="h5">
                        <?=$arr_content['content']?>
                </div>
        </div>
</div>
<!-- /container -->


<!-- footer -->
<!-- footer end -->



</body>

</html><?php ob_out();?>