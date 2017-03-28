<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>交大公共资源申请后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/public/css/Admin/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
        @media (max-width: 980px) {
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
    </style>
    <link href="/public/css/Admin/bootstrap-responsive.css" rel="stylesheet">

</head>

<body>
<?php include 'header.phtml';?>


<div class="container-fluid">
    <div class="row-fluid">
        <?php include 'menu.phtml';?>
        <div class="span9">
            <ul class="breadcrumb">
                <li>公共资源申请</a> <span class="divider">/</span></li>
                <li class="active">申请管理</li>
            </ul>
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#">申请列表</a>
                </li>
            </ul>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>申请人</th>
                    <th>申请组织</th>
                    <th>申请理由</th>
                    <th>申请时间</th>
                    <th>申请情况</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$vo.id}</td>
                        <td>{$vo.name}</td>
                        <td>{$vo.sort}</td>
                        <td>{$vo.click}</td>
                        <td>{$vo.time|date="Y/m/d",###}</td>
                        <td>
                            <div class="btn-group">
                                <a class="btn ajax" href=""><em class="icon-edit"></em></a>
                                <a class="btn ajax" id="remove" href="" target="_self"><em class="icon-remove"></em></a>
                            </div>
                        </td>

                    </tr>
                 </tbody>

            </table>
        <div style="margin:auto;width:280px;font-size:18px">{$page}</div>
    </div>
</div><!--/span-->

</div><!--/row-->

<hr>

<footer>
    <p>&copy; Company 2014</p>
</footer>

</div><!--/.fluid-container-->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/public/js/jquery.min.js"></script>
<script src="/public/js/Admin/bootstrap.js"></script>


</body>
</html>