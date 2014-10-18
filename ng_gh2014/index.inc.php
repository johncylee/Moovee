<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?></title>
<meta name="description" content="<?php echo $description;?>" />
<meta name="keywords" content="<?php echo $keywords;?>" />
<?php include("../includes/header.test.inc.php"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
</head>
<body ng-app="mooveeApp">
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="."><?php echo $title;?></a>
            <p id="extlinks" class="navbar-text pull-right"><?php echo $extlinks;?></p>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div id="breadcrumb" class="well well-small dropdown">
        <div class="btn-group">
            <span class="btn btn-primary">排序方法</span><span id="bCat-dropdown" class="dropdown-toggle btn btn-primary" data-toggle="dropdown"><b class="caret"></b></span>
            <ul id="tabs" class="dropdown-menu" role="menu" aria-labelledby="bCat-dropdown">
                <li role="menuitem"><a class="tab" href="#CATEGORY">影展分類</a></li>
                <li role="menuitem"><a class="tab" href="#DATE">播映日期</a></li>
                <li role="menuitem"><a class="tab" href="#PLACE">播映影廳</a></li>
                <li role="menuitem"><a class="tab" href="#GRADE">電影分級</a></li>
                <li role="menuitem"><a class="tab" href="#REMARK">場次備註</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid" ng-controller="ItemsCtrl">
    <div class="row-fluid">
        <div id="lPanel" class="span5 well">
            <table class="table">
                <tr ng-repeat="item in items">
                    <td>{{item.CTITLE}}</td>
                    <td>{{item.PLACE}}</td>
                    <td>{{item.REMARK}}</td>
                    <td>{{item.GRADE}}</td>
                </tr>
            </table>
            <div id="remarkDesc"><?php echo $remarkDesc;?></div>
        </div>
        <div id="dropBox" class="span7 well well-small"></div>
        <!--<div id="calendar" class="span5 well well-small" style="height: 600px;"></div>-->
    </div>

    <?php include("footer.inc.php");?>
</div>
<div id="varStor" class="hidden"><?php if(strlen($movs) > 0) echo $movs;?></div>
<div id="filter" class="hidden"></div>
</body>
<script src="app.js"></script>
</html>
