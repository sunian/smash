<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sun
 * Date: 10/13/13
 * Time: 11:13 PM
 * To change this template use File | Settings | File Templates.
 */

?>
    <meta http-equiv="X-UA-Compatible" content="IE=9"/>
    <meta name="viewport" content="user-scalable=false"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://datejs.googlecode.com/files/date.js"></script>
    <script type="text/javascript" src="scripts/moment.min.js"></script>
    <script type="text/javascript" src="scripts/isaac.js"></script>
    <script type="text/javascript" src="scripts/bCrypt.js"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/eggplant/jquery-ui.min.css"
          rel="stylesheet"
          type="text/css"/>
    <link href="styles/default.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="scripts/Helper.js"></script>
<?php if (class_exists("SearchBox")) echo "<script type='text/javascript' src='scripts/SearchBox.js'></script>", "\n"; ?>
<?php if (class_exists("Crypto")) echo "<script type='text/javascript' src='scripts/cryptico.min.js'></script>", "\n"; ?>