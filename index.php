<?php

/**
 * Maurice Mol
 * mauricemol@hotmail.nl
 */

require('includes/setup.php');

?>
<div class="container-fluid" id="wrapper" style="padding-left: 0;padding-right: 0;">

    <?php
    require('includes/header.php');

    echo "<div data-sticky-wrap style='overflow: hidden;margin-left: 15px;margin-right: 15px;'>";

    //Homepage
    require("includes/home.php");    

    ?>
    <div class="push"></div>
</div>
<?php

require('includes/footer.php');

?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

<script src="/includes/js/bootstrap.min.js"></script>
<script src="/includes/js/scripts.js"></script>

<!--[if lt IE 9]>
<script src="/includes/js/html5shiv.min.js"></script>
<script src="/includes/js/respond.min.js"></script>
<![endif]-->

</body>
</html>
