<?=include_minified($BC->_getFolder('js').'jquery/lightbox/css/jquery.lightbox-0.5.css','css')?>
<script>var LightBoxPath = "<?=base_url().$BC->_getFolder('js')?>jquery/lightbox/";</script>
<?=include_minified($BC->_getFolder('js').'jquery/lightbox/js/jquery.lightbox-0.5.js','js')?>
<script>
//<![CDATA[
$j(function(){
	$j('a.product-image, a.lightbox, a[rel=lightbox]').lightBox();
});
//]]>
</script>