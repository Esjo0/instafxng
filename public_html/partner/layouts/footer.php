
        <!-- Footer Section: Copyright, Site Map  -->
        <footer id="footer" class="super-shadow">
            
            
            <div class="container-fluid no-gutter copyright">
                <div class="col-sm-12">
                    <p class="text-center">&copy; <?php echo date('Y'); ?>, All rights reserved. Instant Web-Net Technologies Limited (www.instafxng.com)</p>
                </div>
            </div>
        </footer>
        
        <!--LiveZilla Tracking Code (ALWAYS PLACE IN BODY ELEMENT)--> 
        <div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
        var script = document.createElement("script");script.async=true;script.type="text/javascript";var src = "https://instafxng.com/livechat/server.php?a=3a1a8&rqst=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script><noscript><img src="https://instafxng.com/livechat/server.php?a=3a1a8&amp;rqst=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt=""></noscript>
        <!--http://www.LiveZilla.net Tracking Code--> 
        
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-69536508-1', 'auto');
            ga('send', 'pageview');
        </script>
        
        <?php if($db_handle) { mysqli_close($db_handle); } ?>