<div class="row">
    <div class="col-md-12"></div>
</div>

<div class="row" id="head_row">
    <div class="col-md-1"></div>
	<div class="col-md-8">
		<div id="c_sidebar" class="sidebar-nav" style="margin-top: 2vw;">
			<div class="navbar" role="navigation" id="navbar_data_parent">
				<div class="navbar-header">
                    <a href="/winkelmand.php"><span style="text-align: right;margin-top: 8px;" id="shopping_cart_head_m"><img style="margin-right: 25px;" src="/src/images/shopping-basket2.png" alt="Winkelmand"/></span></a>
                    <a href="/index.php" id="m_title" style="font-size: 8vw;margin-left: 5vw;color: black;display: none;text-decoration: none;">Kattenoppas Groningen & Emmen</a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_menu_nav" style="margin-left: 15px;background-color: white;float: left;" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <span class="visible-xs navbar-brand" data-toggle="collapse" data-target=".sidebar-navbar-collapse" style="padding-left: 5px;">Categorie&euml;n</span>
                </div>
				<div id="main_menu_nav" class="navbar-collapse collapse sidebar-navbar-collapse">
					<ul id="main_menu_nav_ul" class="nav navbar-nav">
                        <li><a href="/index.php" id="d_title" style="font-size: 35px;margin-left: 1vw;color: black;padding-right: 0;">Kattenoppas Groningen & Emmen</a></li>
                        <li style="margin-right: 15px;"></li>
                        <?php
                            $core->writeMenu();
                        ?>
						<li class="main_menu_li" style="color: black;"><a href="/Onze Winkel" style="margin-right: 4vw;padding-top: 0;color: black;" class="m_main_men_nav">ONZE WINKEL</a></li>
                        <li class="main_menu_li" style="color: black;"><a href="/winkelmand.php" style="padding-top: 0;color: black;" class="m_main_men_nav">WINKELMAND</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    <div class="col-md-3"></div>
</div>
