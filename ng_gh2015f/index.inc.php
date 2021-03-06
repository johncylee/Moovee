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
            <a class="brand" href=".">Moovee -- 2015 Golden Horse Fantastic Film Festival</a>
            <p id="extlinks" class="navbar-text pull-right"><a class="navbar-link" href="http://playpcesor.blogspot.com/2009/10/moovee-2009.html" title="使用說明 by 電腦玩物">使用說明</a> |
             <a class="navbar-link" href="http://www.ghfff.org.tw/">2015 台北金馬奇幻影展官方網站</a></p>
        </div>
    </div>
</div>

<div class="container-fluid" ng-controller="ItemsCtrl">
    <div class="row-fluid">
        <div class="span6 no-print">
            <div>
                排序清單
                <select ng-model="predicate">
                    <option value="">無</option>
                    <option value="CATEGORY">依照 分類</option>
                </select>
            </div>
            <div>
                過濾清單
                <select ng-model="filterStr">
                    <option value="{{f}}" ng-repeat="f in filterOpts">{{f}}</option>
                </select>
                <button class="btn" ng-disabled="!filterStr" ng-click="filterStr=''">不過濾</button>
            </div>
            <div class="fixed-height">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>片名</td>
                            <td>場次</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="group in groups | orderBy:predicate | filter: filterStr">
                            <td>
                                <div>
                                    <h4>{{group.CTITLE}}</h4>
                                    <a class="" ng-click="$event.stopPropagation()" ng-href="http://www.wallagroup.com/search/?q={{group.ETITLE}}" target="_blank" title="在 Wallagroup 上面尋找">
                                        <img src="../icon/walla.png" alt="在 Wallagroup 上面尋找">
                                    </a>
                                </div>
                                <span class="pull-right h-gutter label">{{group.CATEGORY}}</span>
                            </td>
                            <td style="min-width:330px;">
                                <div ng-repeat="item in group.items"  ng-class-even="'itemEvent'" ng-class-odd="'itemOdd'">
                                    <span class="pull-right" ng-if="group.REMARK">({{item.REMARK}})</span>
                                    {{item.START_DATETIME | date:'M/dd(EEE) H:mm'}} - {{item.PLACE}}
                                    <span class="label label-success" ng-if="item.chosen && !item.conflict" ng-click="rmMovie(item)">已加入片單</span>
                                    <span class="label label-important" ng-if="item.chosen && item.conflict" ng-click="rmMovie(item)">已加入片單(有時間衝突)</span>
                                    <button class="btn btn-small" ng-if="!item.chosen" ng-click="addMovie(item)">+</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="span6">
            <div>
                <h2>我的片單</h2> 已選 {{chosen.length}} 部
                <button class="btn btn-link no-print" ng-disabled="chosen.length <= 0" ng-click="print()">列印</button>
                <button class="btn btn-link no-print" ng-disabled="chosen.length <= 0" ng-click="share()">分享</button>
                <div ng-if="showLink" class="no-print">我的片單連結 <input value="{{link()}}"></div>
            </div>
            <div class="fixed-height">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr ng-repeat="item in chosen" ng-class="{error: item.conflict}">
                            <td>
                                <h3>{{item.CTITLE}}</h3>
                                <h5>{{item.ETITLE}}</h5>
                                <a class="no-print" ng-click="$event.stopPropagation()" ng-href="http://www.wallagroup.com/search/?q={{item.ETITLE}}" target="_blank" title="在 Wallagroup 上面尋找">
                                    <img src="../icon/walla.png" alt="在 Wallagroup 上面尋找">
                                </a>

                                <p class="text-info">片長：{{item.DURATION}} 分鐘</p>
                                <span class="pull-right h-gutter badge badge-success" title="手冊第 {{item.PAGE}} 頁">p.{{item.PAGE}}</span>
                                <span class="pull-right h-gutter label">{{item.CATEGORY}}</span>
                                <span class="pull-right h-gutter badge" title="分級" ng-if="item.GRADE">{{item.GRADE}}</span>
                                <span class="pull-right h-gutter" ng-if="item.REMARK">{{item.REMARK}}</span>
                                <div>
                                </div>
                            </td>
                            <td>
                                <div style="min-height:40px;">
                                    <button class="btn btn-mini pull-right no-print" type="button" ng-click="rmMovie(item)">刪除</button>
                                </div>
                                <p>{{item.PLACE}}</p>
                                <p>
                                    {{item.START_DATETIME | date:'M/dd (EEE)'}}
                                </p>
                                <p>
                                    <span>{{item.START_DATETIME | date:'H:mm'}}</span>
                                    -
                                    <span>{{item.END_DATETIME | date:'H:mm'}}</span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--<div id="calendar" class="span5 well well-small" style="height: 600px;"></div>-->
    </div>
</div>
<div class="container-fluid">
    <div class="">
        <dl class="dl-horizontal">
            <dt>★ </dt><dd>導演或影人出席映後或映前座談</dd>
            <dt>▲</dt><dd>影片拷貝非英語發音且無英文字幕</dd>
        </dl>
    </div>
</div>

<div class="container-fluid">
    <div id="footer" class="row-fluid">
    <div class="fb_like span1"><object data="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcornguo.atcity.org%2Ftest%2Fmoovee%2F&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; height:21px;" type="text/html"></object></div>
    <div class="span11">
        節目資訊以節目手冊與台北電影節佈告為準，本頁面僅供輔助參考<br />
        Created by <a href="http://about.me/cornguo">CornGuo</a> @ 20141015<a href="../changelog.txt" title="changelog">,</a>
        原本以為已經沒人用了，結果有影友提醒我要更新 XD<br />
        Updated by <a href="http://www.wallagroup.com/">wallagroup</a> @ 20150321
    </div>
</div>
</div>


<div id="varStor" class="hidden"></div>
<div id="filter" class="hidden"></div>
</body>
<script src="app.js"></script>
</html>
