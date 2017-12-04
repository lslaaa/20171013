<?php !defined('LEM') && exit('Forbidden');?><?php sub_template_check('template/index/news_show|template/index/header', '1511601727', 'template/index/news_show');?><!DOCTYPE html>
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
<div class="hed_lf"><a href="/find/news"><i class="icon iconfont icon-sanjiao"></i></a></div>
<div class="hed_ct">新闻公告</div>
</div>
<!-- 头部end -->

<!-- container -->
<div class="container">
<div class="announ_cement">
<p class="h3"><?=$arr_news_one['main']['title']?></p>
<p class="h4"><?php echo date('Y年m月d日 H:i:s',$arr_news_one['main']['in_date']); ?></p>
<div class="h5">
<?=$arr_news_one['detail']['content']?>
</div>
</div>
</div>
<!-- /container -->


<!-- footer -->
<div id="footer"></div>
<div class="footer">
<ul>
<li class="active"><a href="#"><i class="icon iconfont icon-shouye-copy-copy-copy"></i>首页</a></li>
<li><a href="#"><i class="icon iconfont icon-youxituijian"></i>游戏</a></li>
<li><a href="#"><i class="icon iconfont icon-weibiaoti-"></i>发现</a></li>
<li><a href="#"><i class="icon iconfont icon-piaoliusanicon-geren-"></i>我的</a></li>
<div class="clearfix"></div>
</ul>
</div>
<!-- footer end -->



</body>

</html><?php ob_out();?>