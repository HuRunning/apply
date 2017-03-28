<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
          <li class="nav-header">后台管理</li>
          <?php
            foreach ($class as $v) {
              echo '<li><a href="/index/listshow/?type='.$v['id'].'">'.$v['name'].'</a></li>';
            }
          ?>
         <!--  <li><a href="{:U('news',array('catid'=>1))}">最新消息</a></li>
          <li><a href="{:U('news',array('catid'=>2))}">实践基地</a></li>
          <li><a href="{:U('newsedit',array('catid'=>0,'id'=>1))}">我要找队友通知</a></li>
          <li><a href="{:U('uploads')}">文件上传</a></li>
          <!-- <li><a href="">在线咨询</a></li> -->
<!--           <li><a href="{:U('team')}">团队信息</a></li>
          <li><a href="{:U('project')}">“双百工程”申报</a></li> -->
        </ul>
   </div><!--/.well -->
</div><!--/span-->