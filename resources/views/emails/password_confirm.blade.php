<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>重置密码链接</title>
</head>
<body>
<h1>重置密码</h1>

<p>
    请点击下面的链接重置密码：
    <a href="{{ route('password.reset_form', $reset->token) }}">
        {{ route('password.reset_form', $reset->token) }}
    </a>
</p>

<p>
    如果这不是您本人的操作，请忽略此邮件。
</p>
</body>
</html>
