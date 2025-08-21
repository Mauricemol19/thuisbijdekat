<?php

/**
 * Maurice Mol
 * mauricemol@hotmail.nl
 */
 
//phpinfo();
//exit();
exit();
require('includes/setup.php');

/*
$db->query("SELECT timestamp, value FROM instagram WHERE name = 'long_lived_access_token'");

if (!empty($row = $db->single())) {
    $timestamp = htmlspecialchars($row["timestamp"]);
    $token = htmlspecialchars($row["value"]);

    $now = date("Y-m-d H:i:s");
    $time = date("Y-m-d H:i:s", strtotime($timestamp . " - 20 days"));

    if ($time < $now) {
        $i_url = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=" . $token;

        $rq = $core->request($i_url);

        $rq = json_decode($rq, true);

        if ($rq !== null) {
            $db->query("UPDATE instagram SET value = :token, timestamp = :timestamp WHERE name = 'long_lived_access_token'");
            $db->bind(":token", $rq["access_token"]);
            $db->bind(":timestamp", date("Y-m-d H:i:s", strtotime($timestamp . " + 20 days")));

            if (!$db->execute()) {
                $core->er_log("Failed to update long_lived_access_token");
            }
        }
    }
}
*/

?>
<div class="container-fluid" id="wrapper" style="padding-left: 0;padding-right: 0;">

    <?php
    require('includes/header.php');

    echo "<div data-sticky-wrap style='overflow: hidden;margin-left: 15px;margin-right: 15px;'>";

    if (!empty($url->getCat())) {
        if (!empty($url->getSubCat())) {
            if (!empty($url->getProduct())) {
                //Product page
                require("includes/product.php");
            } else {
                //Sub cat page
                require("includes/subcategory.php");
            }
        } else {
            //Cat page
            require("category.php");
        }
    } elseif (!empty($url->getPage()) && $url->getPage() == "Blog") {
        //Blog
        require("includes/blog.php");
    } elseif (!empty($url->getPage()) && $url->getPage() != "index.php") {
        //Pages
        require("includes/pages.php");
    } else {
        //Homepage
        require("includes/home.php");
    }

    ?>
    <div class="push"></div>
</div>
    <?php

    require('includes/footer.php');

?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.0.2/dist/jBox.all.min.js"></script>

<script src="/includes/js/bootstrap.min.js"></script>
<script src="/includes/js/lazysizes.js"></script>
<script src="/includes/js/scripts.js"></script>

<script>	
/*	
new jBox('Notice', {
	content: 'Wegens vakantie worden bestellingen alleen op 10 en 17 januari verzonden.',
	color: 'grey',
	attach: '.tooltip',
	target: '#navbar_data_parent',
	autoClose: 10000,
	fixed: true,	
	position: {
		x: 'top',
		y: 'top'
	},
	outside: 'y',
	offset: {x: 0, y: 100}
});
*/
</script>

<?php
if (!empty($url->getProduct())) { ?>
    <script src="/includes/js/shoppingcartAdd.js"></script>
    <script src="/includes/js/productFade.js"></script>
<?php } ?>

<!--[if lt IE 9]>
<script src="/includes/js/html5shiv.min.js"></script>
<script src="/includes/js/respond.min.js"></script>
<![endif]-->

</body>
</html>
