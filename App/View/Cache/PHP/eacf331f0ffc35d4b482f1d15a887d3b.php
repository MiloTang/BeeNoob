<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BeeNoob">
    <meta name="author" content="BeeNoob">
    <link rel="icon" type="image/png" href="/favicon.png">
    <title>BeeNoob</title>

</head>
<body>
<div style="text-align: center">
<form action="/index/upload/" method="post" enctype="multipart/form-data">
    <input type="file" name="file1" ><br/>
    <input type="file" name="file[]" multiple="multiple"><br/>
    <input type="hidden" name="token" value=<?php echo $token; ?>>
    <input type="submit" value="submit">
</form>

</div>
</body>
</html>