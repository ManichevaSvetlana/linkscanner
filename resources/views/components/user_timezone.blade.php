<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
</head>
<body id="body">
</body>
<script>
    window.onload = function(e){
        var date = new Date().toString();
        date = date.split(' ')[5];
        console.log(date);
        console.log(new Date());
        document.body.innerHTML += date;
    }
</script>
</html>
