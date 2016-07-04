<?php wp_footer(); ?>

<footer>
    <?php dynamic_sidebar('Footer'); ?>
    <p class="copyright">Copyright &copy; <?php echo date("Y"); ?> WERQ Fitness, LLC, All rights reserved. <a href="#">Terms & Conditions.</a><p><a rel="nofollow" href="http://www.wecre8design.com" target="_blank">Design by We Cre8 Design</a> | <a rel="nofollow" href="http://www.webvolutionchicago.com" target="_blank">Development by Webvolution</a></p>
</footer>

<script type="text/javascript"><?php
	$client = 'UA-50280504-1';
	$webv = 'UA-32663257-22';
?>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '<?=$client?>', 'auto');ga('send', 'pageview');var _gaq = _gaq || [];_gaq.push(['_setAccount', '<?=$webv?>']);_gaq.push(['_trackPageview']);(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script></body></html>