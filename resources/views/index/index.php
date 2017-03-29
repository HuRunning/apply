<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
		<title>公共空间申请</title>
		<link rel="stylesheet" type="text/css" href="/public/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="/public/css/jquery-ui.min.css" />
		<link rel="stylesheet" type="text/css" href="/public/css/style.css" />
	</head>
	<body>
		<div class="container">
			<div class="left_box">
				<h3>场地选择</h3>
				<ul class="org_select list-unstyled">
					<?php
					   foreach ($org as  $value) {
               if ($value['id']==$orgid) {$string_style='style="background-color: #64af62;"';} else $string_style='';
					     echo "<li ><a $string_style href='/?org="."{$value['id']}"."'>{$value['name']}</a></li>";
					   }
					?>
				</ul>
			</div>
			<div class="right_box">
				<div class="container_top">
					<div class="header_left">
						<h3>申请资源</h3>
						<div class="room_select">
							<select id="room_select">
								<?php
								   foreach ($class as  $value) {
                       $select="";
                       if ($value['id']==$classNow['id']) $select="selected";
								       echo "<option value={$value['id']} {$select}>{$value['name']}</option>";
								   }
								?>
							</select>
						</div>
					</div>
					<div class="header_center">
						<div class="week_select">
							<div class="week_pre">
								<a href="#"></a>
							</div>
							<div class="week_cur">第13周</div>
							<div class="week_nex">
								<a href="#"></a>
							</div>
						</div>
					</div>
					<div class="header_right">
						<ul class="user_info list-unstyled">
							<li><span>你当前的身份：<?php if ($auth) {echo $user;} else echo "游客"; ?></span></li>
							<?php if ($auth) {?><li><a href="/Index/loginout">登出</a></li> <?php } else {?><li><a href="/Index/login">登入</a></li> <?php };?>
              <?php if ($auth) {echo '<li><a id="user_info" href="javascript:;">申请记录</a></li>';} 
               else echo  '<li>申请记录</li>';?>
						</ul>
					</div>
				</div>
				<div class="content">
					<ul class="time_line list-unstyled">
					 <?php
					    for ($i=$classNow['start']; $i<=$classNow['end']; $i++) { 
					    	echo "<li>{$i}:00</li>";
					    }
					 ?>
					</ul>
					<div class="time_table">

						<?php
						   for ($i=0; $i <7 ; $i++) { 
						   	   echo '<div class="tb_day">
							        	<div class="tb_header">
											<div>'.$act[$i]['week'].'</div>
											<div class="tb_date">'.$act[$i]['time'].'</div>
										</div>
									<div class="tb_body">';
	                            for ($j=0; $j <$act[$i]['length'] ; $j++) { 
	                            	$top=(date('H',$act[$i][$j]['starttime'])+date('i',$act[$i][$j]['starttime'])/60-$classNow['start'])*40;
	                            	$height=($act[$i][$j]['endtime']-$act[$i][$j]['starttime'])/3600*40;
	                            	$status=$act[$i][$j]['status']?'':' verifying';
	                            	$timespark=date('H:i',$act[$i][$j]['starttime']).'-'.date('H:i',$act[$i][$j]['endtime']);
	                            	?>
	                            	<div style="top: <?php echo($top)?>px; height: <?php echo($height)?>px;" class="occupied<?php echo($status)?>" data-id="<?php echo($act[$i][$j]['id'])?>" data-name="<?php echo($act[$i][$j]['name'])?>" data-org="<?php echo($act[$i][$j]['org'])?>" data-phone="<?php if ($auth) {echo $act[$i][$j]['phone'];} else {echo(substr_replace($act[$i][$j]['phone'],"****",3,4));};?>"
                                <?php 
                                    // This block is added by oziroe on Nov 13, 2016.
                                    // For displaying apply time for admin.
                                    if ($audit) {
                                ?>
                                data-apply-time="<?php echo date('Y/m/d H:i', $act[$i][$j]['applytime']) ?>"
                                <?php
                                    }
                                    // For displaying cancel button.
                                ?>
                                data-cancelable="<?php echo
                                    $act[$i][$j]['netid'] === session('netid') ? 1 : 0 ?>">
                                <!-- The modify ends here. -->
                                <div class="occupied_text"><?php echo($act[$i][$j]['reason'])?></div><div class="occupied_time"><?php echo($timespark)?></div><div class="handle_person"><?php if ($act[$i][$j]['status']==1) echo "审核人:".$act[$i][$j]['handleperson'] ?></div></div>
	                            	<?php
	                            }

								echo"</div>
								 </div>";
						    	
						    } 
						?>
					</div>
				</div>
			</div>
		</div>














<div class="modal fade" id="apply" tabindex="-1" role="dialog" aria-labelledby="applyLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="applyLabel">活动室申请</h4>
      </div>
      <div class="modal-body">
<form id="form" class="form-horizontal" role="form">
  <div class="form-group">
    <label for="room" class="col-sm-2 control-label">申请空间</label>
    <div class="col-sm-10">
        <select id="room" name="room" class="form-control">
          <?php
             foreach ($class as  $value) {
                 $select="";
                 if ($value['id']==$classNow['id']) $select="selected";
                 echo "<option value={$value['id']} {$select}>{$value['name']}</option>";
             }
          ?>
        </select>
    </div>
  </div>

  
  <!--如果$orgid==5 theme为"default"
  classify为活动真正主题，在数据库中仍记录在classify字段，
  -->
  <?php if($orgid==5){ ?>
  <div class="form-group" hidden>
    <label for="theme" class="col-sm-2 control-label">活动主题</label>
    <div class="col-sm-10">
      <input id="theme" name="theme" type="text" class="form-control" placeholder="" value="default">
    </div>
  </div>
  <? }else{?>
  <div class="form-group" >
    <label for="theme" class="col-sm-2 control-label">活动主题</label>
    <div class="col-sm-10">
      <input id="theme" name="theme" type="text" class="form-control" placeholder="" >
    </div>
  </div>
  <?}?>
  <div class="form-group" <?php if(!$classify){echo "hidden";};?>>
    <label for="room" class="col-sm-2 control-label"><? if($orgid==5){echo "活动主题";}else{echo "活动分类";}?></label>
    <div class="col-sm-10">
        <select id="classify" name="classify" class="form-control">
          <?php
            if($classify){ foreach ($classify as  $value) {
                               $select="";
                 if ($value['id']=='8') $select="selected";
                echo "<option value=".$value['id']." {$select}>{$value['classfication']}</option>";
             }
             }else{echo "<option value='1'></option>";}
          ?>
        </select>
    </div>
  </div>






  <div class="form-group">
    <label for="activity" class="col-sm-2 control-label">活动内容</label>
    <div class="col-sm-10">
      <input id="activity" name="activity" type="text" class="form-control" placeholder="">
    </div>
  </div>
<div class="form-group">
    <label for="people" class="col-sm-2 control-label">活动人数</label>
    <div class="col-sm-10">
        <select id="people" name="people" class="form-control">
          <?php
          for($x=$classNow['peomin'];$x<=$classNow['peomax'];$x++)
          {
            echo "<option value=".$x."} >".$x."</option>";
          }
             
          ?>
        </select>
    </div>
  </div>
 <div class="form-group">
    <label for="other" class="col-sm-2 control-label">其他需求</label>
    <div class="col-sm-10">
      <input id="other" name="other" type="text" class="form-control" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="org" class="col-sm-2 control-label">申请组织</label>
    <div class="col-sm-10">
      <input id="org" name="org" type="text" class="form-control" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">申请人</label>
    <div class="col-sm-10">
      <input id="name" name="name" type="text" disabled value=<?php echo $user ?> class="form-control" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="col-sm-2 control-label">手机号</label>
    <div class="col-sm-10">
      <input id="phone" name="phone" type="text" class="form-control" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="phone" class="col-sm-2 control-label">email</label>
    <div class="col-sm-10">
      <input id="email" name="email" type="text" class="form-control" placeholder="">
    </div>
  </div>
  <div class="form-group">
    <label for="time" class="col-sm-2 control-label">时间</label>
    <div class="col-sm-10">
	    <label for="date" class="sr-only control-label">日期</label>
	    <div class="col-sm-3">
	      <p id="date" name="date" class="form-control-static">10月10日</p>
	    </div>
    	<label for="timeA" class="sr-only control-label">从</label>
    	<div class="col-sm-4">
			<select id="timeA" name="timeA" class="form-control">
				 <?php
            for ($i=$classNow['start']; $i<$classNow['end']; $i++) { 
              $select="";
              if ($i==(int)(($classNow['end']+$classNow['start'])/2-1)) $select="selected";
              echo "<option value='{$i}:00' {$select}>{$i}:00</option>";
              echo "<option value='{$i}:30'>{$i}:30</option>";
            }
            echo "<option value='{$i}:00'>{$i}:00</option>";
         ?>
			</select>
    	</div>
    	<label for="timeB" class="col-sm-1 control-label">到</label>
    	<div class="col-sm-4">
			<select id="timeB" name="timeB" class="form-control">
				 <?php
				    for ($i=$classNow['start']; $i<$classNow['end']; $i++) { 
              $select="";
              if ($i==(int)(($classNow['end']+$classNow['start'])/2+1)) $select="selected";
              echo "<option value='{$i}:00' {$select}>{$i}:00</option>";
				    	echo "<option value='{$i}:30'>{$i}:30</option>";
				    }
				    echo "<option value='{$i}:00'>{$i}:00</option>";
				 ?>
			</select>
    	</div>
    </div>
    <div class="col-sm-10 col-sm-offset-2">
      <div id="slider" style="margin-top:20px;"></div>
    </div>
  </div>
</form>
      <?php echo $classNow['notice']; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button id="submit" type="button" class="btn btn-primary" data-dismiss="modal">提交</button>
      </div>
    </div>
  </div>
</div>
























<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="infoLabel">详细信息</h4>
      </div>
      <div class="modal-body">
<form class="form-horizontal" role="form">
  <div class="form-group" style="display:none">
    <label for="data_id" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
        <p id="data_id" class="form-control-static"></p>
    </div>
  </div>
  <div class="form-group">
    <label for="room_info" class="col-sm-2 control-label">申请教室</label>
    <div class="col-sm-10">
        <p id="room_info" class="form-control-static">团委会议室</p>
    </div>
  </div>
  <div class="form-group">
    <label for="activity_info" class="col-sm-2 control-label">活动</label>
    <div class="col-sm-10">
      <p id="activity_info" class="form-control-static"></p>
    </div>
  </div>
  <div class="form-group">
    <label for="org" class="col-sm-2 control-label">申请组织</label>
    <div class="col-sm-10">
      <p id="org_info" class="form-control-static"></p>
    </div>
  </div>
  <div class="form-group">
    <label for="name_info" class="col-sm-2 control-label">申请人</label>
    <div class="col-sm-10">
      <p id="name_info" class="form-control-static"></p>
    </div>
  </div>
  <div class="form-group">
    <label for="phone_info" class="col-sm-2 control-label">手机号</label>
    <div class="col-sm-10">
      <p id="phone_info" class="form-control-static"></p>
    </div>
  </div>
  <div class="form-group">
    <label for="time" class="col-sm-2 control-label">时间</label>
    <div class="col-sm-10">
	    <label for="date_info" class="sr-only control-label">日期</label>
	    <div class="col-sm-3">
	      <p id="date_info" class="form-control-static"></p>
	    </div>
    	<label for="time_info" class="sr-only control-label"></label>
    	<div class="col-sm-6">
    	  <p id="time_info" class="form-control-static"></p>
    	</div>
    </div>
  </div>
  <!-- Modified by oziroe on Nov 13, 2016.
       Add apply time to admin view. -->
    <?php if ($audit) { ?>
    <div class="form-group">
        <label for="apply-time" class="col-sm-2 control-label">申请时间</label>
        <div class="col-sm-10">
            <p id="apply-time" class="form-control-static"></p>
        </div>
    </div>
    <?php } ?>
  <!-- Modify ends here. -->  
    <div class="form-group">
    <label for="status_info" class="col-sm-2 control-label">审核状态</label>
    <div class="col-sm-10">
      <div id="status_info" class="alert alert-success text-center col-sm-3" role="alert">已通过</div>
    </div>
  </div>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <?php if ($audit){ ?>
            <button id="pass" type="button" class="btn btn-primary" data-dismiss="modal">通过</button>
            <button id="decline" type="button" class="btn btn-primary" data-dismiss="modal">不通过</button>
        <?php
        }
        ?>
        <!-- This code is modified by oziroe on Nov 13, 2016 to add cancel function. -->
        <button id="cancel" type="button" class="btn btn-danger hidden" data-dismiss="modal">撤销</button>
        <!-- The modify ends here. -->
      </div>
    </div>
  </div>
</div>




































<div class="modal fade" id="user_apply" tabindex="-1" role="dialog" aria-labelledby="userLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="userLabel">申请记录</h4>
      </div>
      <div class="modal-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="col-sm-2">日期</th>
          <th class="col-sm-2">时间</th>
          <th class="col-sm-4">活动名</th>
          <th class="col-sm-2">审核状态</th>
          <th class="col-sm-2">拒绝原因</th>
        </tr>
      </thead>
      <tbody>
          <?php
            foreach ($history as $key=>$v)
              {
                 switch ($v['status']) {
                   case 0:
                     $string="warning";
                     $string2="待审核";
                     break;
                   case 1:
                     $string="success";
                     $string2="通过";
                     break;
                   case 2:
                     $string="danger";
                     $string2="未通过";
                     break;
                   // Modified by oziroe on Nov 13, 2016.
                   // To show cancelled history.
                   case -1:
                       $string = '';
                       $string2 = '已撤销';
                       break;
                   default:
                     $string="danger";
                     $string2="未知态";
                     break;
              }
            echo '<tr class="',$string,'">';
            echo '<td class="col-sm-2">',$v['date'],'</td>';
            echo '<td class="col-sm-2">',$v['time1'],'-',$v['time2'],'</td>';
            echo '<td class="col-sm-4">',$v['reason'],'</td>';
            echo '<td class="col-sm-2">',$string2,'</td>';
            echo '<td class="col-sm-2">',$v['decreason'],'</th>';
            echo '</tr>';
              }
   
         ?>

    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<div class="footer">
  <div class="width">
    <div class="about">
      <div class="title">关于挑战网</div>
      <div class="content_1">我们专注交大学生至关重要的需求，以卓越人才和领先技术，致力解决最具挑战的议题，提供包括资讯、维权、活动、视频和招聘就业等广泛领域的解决方案，我们构建、驱动、载运，创想为本，行重于言。我们，想到，做到。</div>
      <div class="us">
        <span style="float:left">关注我们：</span>
        <span class="zj">向设计团队致敬<img src="/public/images/links.png" width="22" height="16"></span>
      </div>
    </div>
    <div class="links">
      <div class="title">友情链接</div>
      <div class="list">
        <ul class="list-unstyled">
          <li><a href="http://www.xjtu.edu.cn" target="_block">西安交通大学</a></li>
          <li><a href="http://news.xjtu.edu.cn" target="_block">交大新闻网</a></li>
          <li><a href="http://www.tiaozhan.com" target="_block">团委挑战网</a></li>
          <li><a href="http://www.eeyes.net" target="_block">e瞳网</a></li>
          <li><a href="http://dean.xjtu.edu.cn" target="_block">西安交大教务处</a></li>
          <li><a href="http://pt.xjtu.edu.cn" target="_block">菩提网</a></li>
        </ul>
      </div>
    </div>
    <div class="others">
      <div class="title">其他</div>
      <div class="list">
        <ul class="list-unstyled"> 
          <li><a href="#">加入我们</a></li>
          <li><a href="#">联系我们</a></li>
          <li><a href="#">隐私声明</a></li>
          <li><a href="#">使用条款</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
		<script type="text/javascript" src="/public/js/jquery.min.js"></script>
		<script type="text/javascript" src="/public/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/public/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/public/js/main.js"></script>
		<script>
      <?php echo "var timeLength = ({$classNow['end']} - {$classNow['start']}) * 2;";?>
      $('.time_table').css('height', 675 + (timeLength - 30) * 20);
			$(function() {
				var selectA = $("#timeA");
				var selectB = $("#timeB");
				var slider = $("#slider");

        var selectValueA = selectA.find('[selected]').index();
        var selectValueB = selectB.find('[selected]').index();
				slider.slider({
					range: true,
					min: 0,
					max: timeLength,
					values: [selectValueA, selectValueB],
					slide: function(event, ui) {
						selectA[0].selectedIndex = ui.values[0];
						selectB[0].selectedIndex = ui.values[1];
					}
				});
				selectA.change(function() {
					slider.slider('values', 0, this.selectedIndex);
				});
				selectB.change(function() {
					slider.slider('values', 1, this.selectedIndex);
				});
        // Modified by oziroe on Nov 13, 2016 for cancel function.
        $('#cancel').on('click', function() {
            $.get('/apply/' + $('#data_id').text() + '/cancel', function (data) {
                alert(data);
                location.reload();
            });
        });
			});
			<?php if ($auth) {echo "var login = true;";} else echo "var login = false;";?>
      <?php echo "var startTime = {$classNow['start']};";?>
      var csrf_token = '<?php echo csrf_token() ?>';      
		</script>
	</body>
</html>
