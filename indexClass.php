<?php
   include 'public/db.php';
   include 'public/same.php';
   session_start();
   if(isset($_GET['classid'])){
    $_SESSION['class_id']=$_GET['classid'];
   }
   $classid= $_SESSION['class_id'];
   // echo $classid;
   if(isset($_POST['keyword'])){
    // var_dump($_POST['keyword']);
     $keyword=$_POST['keyword'];
     $sql="select topic.*,class.name,user.img,user.username from topic,class,user where topic.class_id=class.id and class.id={$classid} and topic.user_id=user.id and title like '%{$keyword}%' order by topic.id desc";
   }elseif(isset($_GET['id'])){
     $id=$_GET['id'];
     $sql="select topic.*,class.name,user.img,user.username from topic,class,user where topic.class_id=class.id and class.id={$classid} and topic.id={$id} and topic.user_id=user.id order by topic.id desc";
      $sqlViews="update topic set views=views+1 where id={$id}";
      $rstviews=$pdo->prepare($sqlViews);
      $rstviews->execute();
    }else{
       $sql="select topic.*,class.name,user.img,user.username from topic,class,user where topic.class_id=class.id and class.id={$classid} and topic.user_id=user.id order by topic.id desc";
    }
   $rst=$pdo->prepare($sql);
   $rst->execute();  
   $rows=$rst->fetchAll();
   
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>学习交流平台</title>
<link rel="stylesheet" type="text/css" href="public/bs/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="public/bs/css/nprogress.css">
<link rel="stylesheet" type="text/css" href="public/bs/css/style.css">
<link rel="stylesheet" type="text/css" href="public/bs/css/font-awesome.min.css">
<link rel="apple-touch-icon-precomposed" href="public/bs/images/icon/icon.png">
<link rel="shortcut icon" href="public/bs/images/icon/favicon.ico">
<script src="public/bs/js/jquery-2.1.4.min.js"></script>
<script src="public/bs/js/nprogress.js"></script>
<script src="public/bs/js/jquery.lazyload.min.js"></script>
<!--[if gte IE 9]>
  <script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="js/html5shiv.min.js" type="text/javascript"></script>
  <script src="js/respond.min.js" type="text/javascript"></script>
  <script src="js/selectivizr-min.js" type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 9]>
  <script>window.location.href='upgrade-browser.html';</script>
<![endif]-->
<style>
.said{
  display: none
}
.head{
  width: 50px;
  height:50px;
  border-radius: 50%
}
*{
  cursor: pointer;
}
</style>
</head>

<body class="user-select">
<header class="header">
  <nav class="navbar navbar-default" id="navbar">
    <div class="container">
      <div class="header-topbar link-border">
        <ul class="site-nav hidden-xs topmenu">
          <li><a href="tags.html">标签云</a></li>
          <li><a href="readers.html" rel="nofollow">读者墙</a></li>
          <li><a href="links.html" rel="nofollow">友情链接</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" rel="nofollow">关注本站 <span class="caret"></span></a>
            <ul class="dropdown-menu header-topbar-dropdown-menu">
              <li><a data-toggle="modal" data-target="#WeChat" rel="nofollow"><i class="fa fa-weixin"></i> 微信</a></li>
              <li><a href="#" rel="nofollow"><i class="fa fa-weibo"></i> 微博</a></li>
              <li><a data-toggle="modal" data-target="#areDeveloping" rel="nofollow"><i class="fa fa-rss"></i> RSS</a></li>
            </ul>
          </li>
        </ul>
        <?php if(!isset($_SESSION['user_id'])){ ?>
        <a data-toggle="modal" data-target="#loginModal" class="login" rel="nofollow">Hi,请登录</a>&nbsp;&nbsp;<a href='#mymodal' data-toggle='modal' class="register" rel="nofollow">我要注册</a>&nbsp;&nbsp;<a href="" rel="nofollow">找回密码</a> 
        <?php }else{?>
         <h4>欢迎<a style='margin-left: 15px' href='user/info.php' class="login"> <?php echo $_SESSION['user_name']?><img class='head' <?php echo "src='user/{$_SESSION['img']}'" ?></a><a hidden class="username"><?php echo $_SESSION['user_name']?></a>
           <a style='margin-left: 15px' class="login" id='loginOut' href="user/loginout.php"> 退出</a></h4>
        <?php } ?> </div>
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar" aria-expanded="false"> <span class="sr-only"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <h1 class="logo hvr-bounce-in"><a href="index.php" title=""><img src="public/bs/images/logo.png" alt=""></a></h1>
      </div>
      <div class="collapse navbar-collapse" id="header-navbar">
        <ul class="nav navbar-nav navbar-right">
          <li class="hidden-index"><a data-cont="平台首页" href="index.php">平台首页</a></li>
          <?php 
           foreach($rowsClass as $row){
             if($row['id']===$classid){
              echo "<li class='active'><a href='indexClass.php?classid={$row['id']}'>{$row['name']}</a></li>";       
             }elseif($row['id']==5) {
                 echo "<li><a href='addarticle.php?classid={$row['id']}'>{$row['name']}</a></li>"; 
             }else{
               echo "<li><a href='indexClass.php?classid={$row['id']}'>{$row['name']}</a></li>";  
             }

           }
          ?>  
        </ul>
        <form class="navbar-form visible-xs" action="indexClass.php" method="post">
          <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="请输入关键字" maxlength="20" autocomplete="off">
            <span class="input-group-btn">
            <button class="btn btn-default btn-search" name="search" type="submit">搜索</button>
            </span> </div>
        </form>
      </div>
    </div>
  </nav>
</header>
<section class="container">
  <div class="content-wrap">
    <div class="content">
      <div class="jumbotron" style="display: none;">
        <h1>欢迎访问ZHM博客</h1>
        <p>在这里可以看到前端技术，后端程序，网站内容管理系统等文章，还有我的程序人生！</p>
      </div>
      <div id="focusslide" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#focusslide" data-slide-to="0" class="active"></li>
          <li data-target="#focusslide" data-slide-to="1"></li>
          <li data-target="#focusslide" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
          <div class="item active"> <a href="" target="_blank"><img src="public/bs/images/banner/banner_01.jpg" alt="" class="img-responsive"></a> 
            <!--<div class="carousel-caption"> </div>--> 
          </div>
          <div class="item"> <a href="" target="_blank"><img src="public/bs/images/banner/banner_02.jpg" alt="" class="img-responsive"></a> 
            <!--<div class="carousel-caption"> </div>--> 
          </div>
          <div class="item"> <a href="" target="_blank"><img src="public/bs/images/banner/banner_03.jpg" alt="" class="img-responsive"></a> 
            <!--<div class="carousel-caption"> </div>--> 
          </div>
        </div>
        <a class="left carousel-control" href="#focusslide" role="button" data-slide="prev" rel="nofollow"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">上一个</span> </a> <a class="right carousel-control" href="#focusslide" role="button" data-slide="next" rel="nofollow"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">下一个</span> </a> </div>
      <article class="excerpt-minic excerpt-minic-index">
        <h2><span class="red">【今日良言】</span><a href="" title=""><?php echo $rowstoday['title'] ?></a></h2>
        <p class="note"><?php echo htmlspecialchars_decode($rowstoday['content']) ?></p>
      </article>
      <div class="title">
        <h3>最新发布</h3>
        <div class="more"><a href="">PHP</a><a href="">JavaScript</a><a href="">EmpireCMS</a><a href="">Apache</a><a href="">MySQL</a></div>
      </div> 
      <?php 
         foreach($rows as $row){      
       ?>
      <article class="excerpt excerpt-1"><a class="focus" <?php echo "href='indexClass.php?id={$row['id']}'"; ?> title=""><img class="thumb" <?php echo "src='user/{$row['img']}'"; ?> alt=""><?php echo "{$row['username']}"; ?></a>
      
        <header><?php echo"<a class='cat' href='indexClass.php?classid={$row['class_id']}'>" ?><?php echo $row['name'] ?><i></i></a>
          <?php echo "<h2><a href='indexClass.php?id={$row['id']}' title=''>{$row['title']}</a></h2>" ; ?>
        </header>
        <p class="meta">
          <time class="time"><i class="glyphicon glyphicon-time"></i> <?php echo date('20y-m-d h:i:s',$row['time']); ?></time></br></br>
          <span class="views"><i class="glyphicon glyphicon-eye-open"></i> 共<span class="articleView"><?php echo "{$row['views']}"; ?></span>人围观</span> <span class="comment" data-toggle='modal'><i class="glyphicon glyphicon-comment"></i> 共<span class="commentTimes"><?php echo "{$row['comment_times']}"; ?></span>条评论</span>
          </p></br>
          <a class="cat" href="javascript:">正文<i></i></a>
          <a class="img-responsive"><?php $str= htmlspecialchars_decode($row['content'])?></a>
          
        <p class="note"><?php $b=strlen($str);if(isset($_GET['id'])){echo $str;} elseif($b<5005){echo "<a href='indexClass.php?id={$row['id']}' style='color:black'>".substr($str , 0 , 5005)."</a>";}else{echo "<a href='indexClass.php?id={$row['id']}' style='color:black'>".substr($str , 0 , 5005)."</a>"."。。。<a style='float:right;margin-right:70px' href='indexClass.php?id={$row['id']}'>点击查看更多>></a>";} ?></p>
         <a class="cat" href="javascript:" style="margin-top: 15px">用户评论：<i></i><i class="glyphicon glyphicon-comment"></i><span class="commentTimes"><?php echo "{$row['comment_times']}" ?></span></a><span class='meta user_comment' style='margin-left: 10%;font-size: 14px'><a class="comment" <?php if(!isset($_SESSION['user_name'])){?> href='#loginModal' <?php } ?> data-toggle='modal'><i class="glyphicon glyphicon-comment"></i>点击评论</a></span>
         <span class='userComment'>
             <?php 
                 $sqlContent = "select content.*,user.username,topic.id from content,user,topic where content.user_id=user.id and content.topic_id=topic.id and topic.id='{$row['id']}' limit 10";
                 $rstContent=$pdo->query($sqlContent);
                 while ($rowContent=$rstContent->fetch()) {
                   echo "<p><strong>{$rowContent['username']} : </strong> {$rowContent['user_content']}</p>";
                 }
             ?>
             </span>
            <div class="said">
              <div></br>
                <input class="ipt form-control" type="text" name="conten" />
                <input type="hidden" name="topic_id" value="<?php echo $row['id'];?>"/>
                <input type="submit" class="btn btn-primary" style="margin-top: 5px" value="提交评论" <?php if(!isset($_SESSION['user_name'])){ echo "disabled"; }?> /> 
                  
              </div>
            </div>
      </article>
      <?php
        }
      ?>  
      </ul>
      <!-- <nav class="pagination" style="display: none;">
        <ul>
          <li class="prev-page"></li>
          <li class="active"><span>1</span></li>
          <li><a href="?page=2">2</a></li>
          <li class="next-page"><a href="?page=2">下一页</a></li>
          <li><span>共 2 页</span></li>
        </ul>
      </nav> -->
    </div>
  </div>
  <aside class="sidebar">
    <div class="fixed">
      <div class="widget widget-tabs">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#notice" aria-controls="notice" role="tab" data-toggle="tab">网站公告</a></li>
          <li role="presentation"><a href="#centre" aria-controls="centre" role="tab" data-toggle="tab">会员中心</a></li>
          <li role="presentation"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab">联系站长</a></li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane notice active" id="notice">
            <ul>            
             <?php foreach ($rowsnotice as $row) {              
                echo "<li>
                <time>".date('m-d',$row['time'])."</time>
                <a data-toggle='modal' data-target='#noticeModal'>{$row['content']}</a></li> ";
              } ?>        
            </ul>
          </div>
          <div role="tabpanel" class="tab-pane centre" id="centre">
            <h4>需要登录才能进入会员中心</h4>
            <p> <a data-toggle="modal" data-target="#loginModal" class="btn btn-primary">立即登录</a> <a href="javascript:;" class="btn btn-default">现在注册</a> </p>
          </div>
          <div role="tabpanel" class="tab-pane contact" id="contact">
            <h2>Email:<br />
              <a href="mailto:4041812@qq.com" data-toggle="tooltip" data-placement="bottom" title="4041812@qq.com">4041812@qq.com</a></h2>
          </div>
        </div>
      </div>
      <div class="widget widget_search">
        <form class="navbar-form" action="indexClass.php" method="post">
          <div class="input-group">
            <input type="text" name="keyword" class="form-control" size="35" placeholder="请输入关键字" maxlength="15" autocomplete="off">
            <span class="input-group-btn">
            <button class="btn btn-default btn-search" name="search" type="submit">搜索</button>
            </span> </div>
        </form>
      </div>
    </div>
    <div class="widget widget_sentence">
      <h3>每日一句</h3>
      <div class="widget-sentence-content">
        <h4><?php echo date('20y年m月d日',$t).' 星期'.mb_substr( "日一二三四五六",date("w"),1,"utf-8" );  ; ?></h4>
        <p><?php echo htmlspecialchars_decode($rowsdaily['content']); ?></p>
      </div>
    </div>
    <div class="widget widget_hot">
      <h3>热门文章</h3>
      <ul>
        <?php 
         foreach($rowshot as $row){      
       ?>
        <li><a href='index.php?<?php echo "id={$row['id']}"; ?>'><span class="thumbnail"><img class="thumb" <?php echo "src='user/{$row['img']}'"; ?> alt=""></span><span class="text"><?php echo "{$row['title']}" ?> </span><span class="muted" style="display: block;line-height: 30px"><i class="glyphicon glyphicon-time"></i> <?php echo date('20y-m-d h:i:s',$row['time']); ?> </span><span class="muted"><i class="glyphicon glyphicon-eye-open"></i> <?php echo "{$row['views']}"; ?></span><span class="muted"><i class="glyphicon glyphicon-comment"></i> <?php echo "{$row['comment_times']}" ?></span></a></li>
        <?php } ?>
      </ul>
    </div>
  </aside>
</section>
<footer class="footer">
  <div class="container">
    <p>&copy; 2016 <a href="">ylsat.com</a> &nbsp; <a href="http://www.miitbeian.gov.cn/" target="_blank" rel="nofollow">豫ICP备20151109-1</a> &nbsp; <a href="sitemap.xml" target="_blank" class="sitemap">网站地图</a></p>
  </div>
  <div id="gotop"><a class="gotop"></a></div>
</footer>
<!--微信二维码模态框-->
<div class="modal fade user-select" id="WeChat" tabindex="-1" role="dialog" aria-labelledby="WeChatModalLabel">
  <div class="modal-dialog" role="document" style="margin-top:120px;max-width:280px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="WeChatModalLabel" style="cursor:default;">微信扫一扫</h4>
      </div>
      <div class="modal-body" style="text-align:center"> <img src="public/bs/images/weixin.jpg" alt="" style="cursor:pointer"/> </div>
    </div>
  </div>
</div>
<!--该功能正在日以继夜的开发中-->
<div class="modal fade user-select" id="areDeveloping" tabindex="-1" role="dialog" aria-labelledby="areDevelopingModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="areDevelopingModalLabel" style="cursor:default;">该功能正在日以继夜的开发中…</h4>
      </div>
      <div class="modal-body"> <img src="public/bs/images/baoman/baoman_01.gif" alt="深思熟虑" />
        <p style="padding:15px 15px 15px 100px; position:absolute; top:15px; cursor:default;">很抱歉，程序猿正在日以继夜的开发此功能，本程序将会在以后的版本中持续完善！</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">朕已阅</button>
      </div>
    </div>
  </div>
</div>
<!--登录模态框-->
<div class="modal fade user-select" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <form action="user/check.php" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="loginModalLabel">登录/注册</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="loginModalUserNmae">用户名</label>
            <input type="text" class="form-control" id="loginModalUserNmae" placeholder="请输入用户名" name="username" autofocus maxlength="15" autocomplete="off" required>
          </div>
          <div class="form-group">
            <label for="loginModalUserPwd">密码</label>
            <input type="password" class="form-control" id="loginModalUserPwd" placeholder="请输入密码" name="password" maxlength="18" autocomplete="off" required>
          </div>
        </div>
        <div class="modal-footer">
        <?php  if(isset($_GET['id'])){ echo "<input type='hidden' name='topic_id' value='{$_GET['id']}'>";} ?>
          <a type="button" class="btn btn-primary pull-left" href='#mymodal' data-toggle='modal' data-dismiss="modal">注册</a>        
          <button type="submit" class="btn btn-primary">登录</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- 注册模态框-->
<div class="modal fade user-select" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
       <form action="regist.php" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="loginModalLabel">用户注册</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="loginModalUserNmae">用户名</label>
            <input type="text" class="form-control" id="loginModalUserNmae" placeholder="请输入用户名" name="username" autofocus maxlength="15" autocomplete="off" required>
          </div>
          <div class="form-group">
            <label for="loginModalUserPwd">密码</label>
            <input type="password" class="form-control" id="loginModalUserPwd" placeholder="请输入密码" name="password" maxlength="18" autocomplete="off" required>
          </div>
          <div class="form-group">
               <lable><h4 style="font-weight: blod">验证码图片</h4></lable></br>
               <img src="public/vcode/vercode.php" onclick="this.src='public/vcode/vercode.php?rand='+Math.random()" />               
        </div>
        <div class="form-group">
              <lable><p><strong>用户输入验证码</strong></p></lable><p></p>
                <input class="form-control" type="text" maxlength="18" autocomplete="off" required name="fcode"/>
              </div>
         </div>     
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">注册</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 公告模态 -->
<div id="noticeModal" class='modal fade'>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="close" data-dismiss='modal'>&times;</div>
            <h3>网站公告</h3>    
          </div>
          <div class="modal-body">
            <div class="tab-content">
            <div role="tabpanel" class="tab-pane notice active" id="notice">
            <ul>            
             <?php foreach ($rowsnotice as $row) {              
                echo "<li style='color:#999999;list-style: binary;margin-left:10px'>                
                <p style='color:#999999;width:520px;'> {$row['content']}</p>
                <p style='text-align:right'>".date('m-d',$row['time'])."</p>
                </li> ";
              } ?>        
            </ul>
          </div>
          </div>
          <div class="modal-footer">
            <button class='btn btn-primary' data-dismiss='modal'>我知道了</button>          </div>
        </div>
      </div>  
</div>
<!--右键菜单列表-->
<!-- <div id="rightClickMenu">
  <ul class="list-group rightClickMenuList">
    <li class="list-group-item disabled">欢迎访问ZHM博客</li>
    <li class="list-group-item"><span>IP：</span>192.168.0.102</li>
    <li class="list-group-item"><span>地址：</span>福建省厦门集美大学</li>
    <li class="list-group-item"><span>系统：</span>Windows10 </li>
    <li class="list-group-item"><span>浏览器：</span>Firefox</li>
  </ul>
</div> -->
<script src="public/bs/js/bootstrap.min.js"></script> 
<script src="public/bs/js/jquery.ias.js"></script> 
<script src="public/bs/js/scripts.js"></script>

<script type="text/javascript">
  $(function(){
      var oSaid=$('.excerpt .said');
      var oCom=$('.excerpt .user_comment .comment');
      var oIput=$('.ipt');  
      var index=0;  
      oCom.click(function(){
        index=$(this).index('.excerpt .user_comment .comment');
        oSaid.stop().hide(800);        
        oSaid.eq(index).stop().toggle(800);
        oIput[index].focus();        
      })
       
     $('#loginOut').click(function(){
       cf=confirm('您确定退出吗?');
      if(!cf){
        return false;
      }
     })
    
    $('.ipt').keydown(function(event){
      if(event.keyCode == '13'){
        addComment($('.said .btn').eq(index))
        //console.log($('.said .btn').eq(index))
      }
    })
    $('.said .btn').click(function(){      
       addComment($(this))
       //console.log($(this))
    })   
    function addComment(_this){ 
      var a=_this.prev().val();
      var b = _this.prevAll().filter(".ipt").val();     
        $.ajax({
          url: 'user/addcontent.php',
          type:'POST',
          data:{
            'topic_id': a,
            'conten':b
          },
          success:function(res){
             var result=JSON.parse(res)
             if(result.status==0){
               var content = $('.userComment').eq(index).html();
               // $('.userComment').eq(index).html(content+`<p><strong> ${$('.username').html()}: </strong> ${b}</p>`);
               //es6 语法 手机端无法识别
               $('.userComment').eq(index).html(content+"<p><strong> "+$('.username').html()+": </strong>"+ b +"</p>");
               $('.commentTimes').eq(index*2).html(Number($('.commentTimes').eq(index*2).html())+1)
               $('.commentTimes').eq(index*2+1).html(Number($('.commentTimes').eq(index*2+1).html())+1)
               $('.articleView').eq(index).html(Number($('.articleView').eq(index).html())+1)
               $('.ipt').val('')
             }
          }

        })     
    }
})
</script>

</body>
</html>