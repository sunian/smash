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
<meta name="viewport" content="width=500"/>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://datejs.googlecode.com/files/date.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/south-street/jquery-ui.css" rel="stylesheet"
      type="text/css"/>
<link href="styles/default.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    function alignCellWidths(rowSource, rowTarget) {
        for (var i in rowTarget) {
            $(rowTarget[i]).css("width", $(rowSource[i]).css("width"));
        }
    }

</script>