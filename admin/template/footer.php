<div class="mt-5 p-5"></div><!-- spacer -->
<footer class="fixed-bottom bg-light mt-5">
    
  <div class="container">
    <div class="text-center p-2 text-muted">
		Copyright &copy; 2002-<?php echo date('Y'); ?> &mdash; Ajmweb.nl 
    </div>
  </div>
</footer>


<?php $debug = 2; ?> 

<?php if($debug == 1) { ?>
<pre><?php print_r($_COOKIE); ?></pre>
<pre><?php print_r($_SESSION); ?></pre>
<?php } ?>
</body>
</html>