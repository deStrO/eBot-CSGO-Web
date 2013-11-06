<div id="fb-root"></div> 
<script>
    $(document).ready(function() {
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
        (function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/de_DE/all.js#xfbml=1&appId=142853269237203";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
        $('.email').each(function() {
            $(this).html('<a href="mailto:' + $(this).attr('data-attr').replace('||', '@') + '" target="_blank">' + $(this).attr('data-attr').replace('||', '@') + '</a>');
        });
        $("#ebot-csgo").hubInfo({ 
            user: "deStrO",
            repo: "eBot-CSGO"
        });
        $("#ebot-web").hubInfo({ 
            user: "deStrO",
            repo: "eBot-CSGO-Web"
        });
    });
</script>

<style>
    h4 { margin-top: 20px; }
</style>

<h1>eBot-CSGO</h1>
<blockquote>
    <p class="lead">The eBot is a full managed server-bot written in PHP and nodeJS.<br>eBot features easy match creation and tons of player and matchstats. Once it's setup, using the eBot is simple and fast.</p>
</blockquote>

<div class="well" style="overflow:hidden; min-height: 497px;">
    <div class="row-fluid">
        <div class="span3" style="text-align:center;">
            <?php echo image_tag("/images/ebot.png", "style='margin: 15px; margin-top:50px'"); ?>
        </div>
        <div class="span9">
            <h4>Production</h4>
                <p>
                <i class="icon-arrow-right" style="margin-right: 8px;"></i>Julien 'destrO' Pardons - <span class="email" data-attr="destro||esport-tools.net"></span><br>
                <em style="margin-left: 22px;">Programming &amp; Development</em><br>
                <span style="margin-left: 22px;"><a href="https://twitter.com/deStrO_BE" class="twitter-follow-button" data-show-count="false">@deStrO_BE</a></span>
            </p>
            <p>
                <i class="icon-arrow-right" style="margin-right: 8px;"></i>Fabian 'Basert' Gruber - <span class="email" data-attr="basert||esport-tools.net"></span><br>
                <em style="margin-left: 22px;">Programming &amp; Development</em><br>
                <span style="margin-left: 22px;"><a href="https://twitter.com/JustBasert" class="twitter-follow-button" data-show-count="false">@JustBasert</a></span>
            </p>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3" style="text-align:center; margin-top: 20px;">
            <div class="fb-like-box" data-href="https://www.facebook.com/esporttools.net" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
        </div>
        <div class="span9">
            <h4>Help &amp; Support</h4>
            <p>If you need support using the eBot, the best way is to check out our forums. Just visit <a href="http://www.esport-tools.net" target="_blank">http://www.esport-tools.net</a></p>
            <h4>Contribute</h4>
            <p>If you are a developer and want to contribute to the eBot project, you can find the whole sourcecode at our github pages:
                <div style="overflow:hidden;">
                    <div id="ebot-csgo" style="width:60%; margin: 15px;"></div>
                    <div id="ebot-web" style="width:60%; margin: 15px;"></div>
                </div>
            </p>
            <h4>License</h4>
            <p>The code is under Creative Commons license. You can find all details here: <a href="http://creativecommons.org/licenses/by/3.0/">http://creativecommons.org/licenses/by/3.0/</a><br>
            You can copy, distribute, modify the source code, but you have to keep the license terms.</p>
            <h4>Donation</h4>
            <p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="business" value="destro@esport-tools.net">
                    <input type="hidden" name="lc" value="BE">
                    <input type="hidden" name="item_name" value="eSport-tools.net">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="EUR">
                    <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                    <input type="image" src="https://www.paypalobjects.com/en_US/BE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
                </form>
                eBot is free to use. If you like it, you can tip us a donation here. We will propably spend it for some more beer.
            </p>
        </div>
    </div>
</div>