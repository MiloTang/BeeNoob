<!DOCTYPE HTML>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>UMEDITOR 完整demo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="Umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="Umeditor/third-party/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="Umeditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="Umeditor/umeditor.min.js"></script>
    <script type="text/javascript" src="Umeditor/lang/zh-cn/zh-cn.js"></script>

</head>
<body>
<h1>UMEDITOR 完整demo</h1>

<!--style给定宽度可以影响编辑器的最终宽度-->
<div style="margin: 100px; width:80%;">
<div type="text/plain" id="myEditor" >
    <p>这里我可以写一些输入提示</p>
</div>
</div>

<div class="clear" style=""></div>
<button class="btn" onclick="getContent()">获得内容</button>
<script type="text/javascript">
    //实例化编辑器
    var um = UM.getEditor('myEditor');
    function getContent() {
        var arr = [];
        arr.push(UM.getEditor('myEditor').getContent());
        alert(arr);
    }
</script>

</body>
</html>