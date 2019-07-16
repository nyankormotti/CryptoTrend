<?php
$i = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>google ニュース</title>
</head>

<body>
    <h1>google news</h1>
    @foreach($news_list as $list)
    <?php $i = $i + 1; ?>
    <div>
        <p>
        <?php echo $i;?>
            <a href="{{$list['url']}}">{{$list['title']}}</a>
        </p>
    </div>

    @endforeach

</body>

</html>