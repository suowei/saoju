<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>跳转页面</title>
</head>
<body>
操作成功，即将跳转……<br>
如未跳转，请点击如下链接<a href="{{ $url }}">{{ $url }}</a>
<script type="text/javascript">
    window.location.href = "{{ $url }}";
</script>
</body>
</html>

