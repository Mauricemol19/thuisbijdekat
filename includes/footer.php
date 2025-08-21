<div class='m_wrapper' id="footer_wrap_id" style="margin-top: 6%; margin-left: 2%;margin-bottom: 2%;">
	<div class="row">
		<div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="m_insta_title" style="font-size: 1vw;color: black;">
                INSTAGRAM FEED
            </div>
        </div>
        <div class="col-lg-2"></div>
	</div>

    <div class="row" style="margin-top: 4%;">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div style="display: flex;flex-wrap: wrap;justify-content: space-around;">
            <?php
                $db->query("SELECT timestamp, value FROM instagram WHERE name = 'long_lived_access_token'");

				if (!empty($row = $db->single())) {
					$timestamp = htmlspecialchars($row["timestamp"]);					

					$url_i = "https://graph.facebook.com/v22.0/17841400223013683/media?fields=id%2Cmedia_url%2Cmedia_type%2Cpermalink&access_token=" . $token;
					$rq = $core->request($url_i);

					if (!empty($rq)) {
						$rq = json_decode($rq, true);

						$rq_array = [];

						for ($y = 0; $y < count($rq["data"]); $y++) {
							if ($rq["data"][$y]["media_type"] == "IMAGE") {
								array_push($rq_array, $rq["data"][$y]);
							}
						}

						for ($x = 0; $x < count($rq_array); $x++) {
							if ($x % 4 == 0 && $x != 0) {
								echo "<div class='break'></div>";
							}

							if ($x < 8) {
								echo "<div class='insta_item'><a href='" . $rq_array[$x]["permalink"] . "' target='_blank'>";
							} else {
								echo "<div class='insta_item' style='display: none;'><a href='" . $rq_array[$x]["permalink"] . "' target='_blank'>";
							}

							echo "<img src='" . $rq_array[$x]["media_url"] . "' /></a></div>";
						}
					}
				}
                ?>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>

    <div class="row" id="m_insta_viewmore" style="margin-top: 1%;">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="viewmore_insta">
                <div id="insta_txt" style="text-align: center;"></div>
                <button type="button" class="form-control" id="insta_more_button" style="width: auto;margin: auto;">Toon meer</button>
            </div>
        </div>
    </div>

	<div class="row" style="margin-top: 3%;">
        <div class="col-lg-2"></div>
		<div class="col-lg-8">
			<div class="flex-container t_footer_flex" id="m_footer_flex">
                <div class="footer_flex_box">
                    HUUSHINNE
                    <ul class="m_footer_u" style="margin-top: 7%;list-style: none;">
                        <li>Dalipassage 2</li>
                        <li>7811DB EMMEN</li>
                        <li>0591-547631</li>
                        <li>info@huushinne.nl</li>
                    </ul>
                </div>
                <div class="footer_flex_box m_footer_flex_box" style="margin-left: 5%;">
                    <div style="width: 100%;">
                        OPENINGSTIJDEN
                    </div>
                    <ul class="m_footer_u" style="margin-top: 7%;margin-right: 2%;list-style: none;display: inline-block;">
                        <li>ma</li>
                        <li>di-vr</li>
                        <li>za</li>
                        <li>zo</li>
                    </ul>
                    <ul class="m_footer_u" style="margin-top: 7%;list-style: none;display: inline-block;">
                        <li>gesloten</li>
                        <li>10:00-17:30 uur</li>
                        <li>10:00-17:00 uur</li>
                        <li>gesloten</li>
                    </ul>
                </div>
                <div class="footer_flex_box m_footer_flex_box" style="margin-left: 5%;">
                    ALGEMEEN
                    <ul class="m_footer_u" style="margin-top: 7%;list-style: none;">
                        <li><a href="/Klantenservice">Klantenservice</a></li>
                        <li><a href="/Privacy Verklaring">Privacy verklaring</a></li>
                        <li><a href="/Algemene Voorwaarden">Algemene voorwaarden</a></li>
                        <li><a href="/Retourneren en Ruilen">Retourneren en Ruilen</a></li>                        
                    </ul>
                </div>
                <div class="footer_flex_box m_footer_flex_box" style="margin-left: 5%;">
                    <div id="m_footer_pages" style="display: none;">Pagina's</div>
                    <ul class="m_footer_u" style="margin-top: 7%;list-style: none;">
                        <li><a href="/Contact">Contact</a></li>
                        <li><a href="/Bezorgen of Afhalen">Bezorgen of Afhalen</a></li>
                        <li><a href="/Klachten">Klachten</a></li>
                        <li><a href="/FAQ">FAQ</a></li>
						<li><a href="/account.php">Mijn account</a></li>
                    </ul>
                </div>
            </div>
		</div>
        <div class="col-lg-2"></div>
	</div>
</div>