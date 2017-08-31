<?php require_once 'header.php'; ?>

<div class="content-section">

<?php /* Title */ ?>
	<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">Thank you</h1>

	<p class="alert alert-success"><?php echo $msg; ?></p>
	
	
	<?php
if($this->uri->uri_string() == 'thank-you/checkout') {  ?>

<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6022352998886', {'value':'0.00','currency':'GBP'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6022352998886&amp;cd[value]=0.00&amp;cd[currency]=GBP&amp;noscript=1" /></noscript>

<!-- Google Code for Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 987387324;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "hYOsCMSxrAUQvKvp1gM";
var google_conversion_value = 1.00;
var google_conversion_currency = "GBP";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/987387324/?value=1.00&amp;currency_code=GBP&amp;label=hYOsCMSxrAUQvKvp1gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php
} else {
 ?>
<!-- Google Code for Contact us Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 987387324;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "wPMvCJrvu1kQvKvp1gM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/987387324/?label=wPMvCJrvu1kQvKvp1gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
 <?php
}
?>


</div>

<?php require_once 'footer.php';