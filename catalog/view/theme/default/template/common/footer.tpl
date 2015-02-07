<footer id="footer" class="wrap">
	<p id="back-top"> <a href="#top" title="На верх"><i class="fa fa-angle-up"></i></a> </p>
	<aside class="footnav">
  <div class="container">
    <div class="spotlight footnav  row">
      <?php if ($informations) { ?>
      <div class="col-sm-4">
        <h3 class="module-title"><?php echo $text_information; ?></h3>
		<b class="click"></b>
		<div class="module-ct">
        <ul class="nav menu">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a><i class="fa fa-angle-right"></i></li>
          <?php } ?> 
        </ul>
		</div>
      </div>
      <?php } ?>
      <div class="col-sm-4">
        <h3 class="module-title"><?php echo $text_extra; ?></h3>
		<b class="click"></b>
		<div class="module-ct">
        <ul class="nav menu">
			<li><a href="<?php echo $news; ?>"><?php echo $text_news; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a><i class="fa fa-angle-right"></i></li>
		  <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a><i class="fa fa-angle-right"></i></li>
        </ul>
		</div>
      </div>
      <div class="col-sm-4">
      <h3 class="module-title"><?php echo $text_account; ?></h3>
	  <b class="click"></b>
		 <div class="module-ct">
        <ul class="nav menu">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a><i class="fa fa-angle-right"></i></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a><i class="fa fa-angle-right"></i></li>
        </ul>
		</div>
      </div>
    </div>
  </div>
  </aside>
  <section class="copyright">
  <div class="container">
  <div class="row">
  <div class="col-md-12">
  <div class="module">
  <span><?php echo $powered; ?></span>
  <?php if(isset($footer_html)) echo $footer_html; ?>
  </div>
  </div>
  </div>
  </div>
  </section>
</footer>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter28186215 = new Ya.Metrika({id:28186215,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/28186215" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body></html>
