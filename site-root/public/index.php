<?php
$businesses = [
    'https://liberatedelephant.com'       => 'Liberated Elephant',
    'https://www.poly-labor.com'          => 'Poly Labor',
    'https://amidknight.com'              => 'Alexander Midknight',
    'https://mastering-the-mundane.com'   => 'Mastering the Mundane',
    'https://intent-agility.com'          => 'Intent Agility',
    'https://4th.earth'                   => '4th Earth',
    'https://the-irreverent-agilists.com' => 'The Irreverent Agilists'
];

$randomized = [];
while (count($businesses) > 0) {
    $randomKey = array_rand($businesses);

    $randomized[$randomKey] = $businesses[$randomKey];

    unset($businesses[$randomKey]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>8fold Professionals</title>
    <style>
        * {
            color: #ffffff;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #27313A;
        }
        article {
            margin: 0 auto;
            max-width: 400px;
            line-height: 1.25rem;
        }

        h1 {
            margin: 0 auto;
            margin-top: 30px;
            background-image: url('./media/logo.svg');
            background-repeat: no-repeat;
            background-size: 100% auto;
            height: 150px;
            width: 300px;
        }

        .sr-only {
            position: absolute;
            left: -10000px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }

        ul {
            line-height: 2rem;
        }

        a {
            color: #0ABAF2;
        }

        footer {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <article>
        <h1><span class="sr-only">8fold Professionals</span></h1>
        <p>8fold Practitioners operate:</p>
        <ul>
            <?php foreach ($randomized as $url => $title) {
                echo '<li><a href="'. $url . '">'. $title . '</a></li>';
            } ?>
        </ul>
        <p>8fold is a not-for-profit collective of microbusiness owners operating in a variety of disciplines.</p>
        <p>Part professional mastermind, part incubator, and part cooperative, 8fold strives to provide products and services to microbusinesses to reduce administrative burden and increase collective bargaining.</p>
    </article>
    <footer>
        <p><small>This site inspired by <a href="https://www.berkshirehathaway.com">Berkshire Hathaway</a>.</small>
        <p><small>&copy; 2019–<?php echo date('Y'); ?> 8fold, llc. All rights reserved.</small></p>
    </footer>
</body>
</html>
