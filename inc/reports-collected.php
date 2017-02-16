<div class="wrap">
	<h1>Reports</h1>


<?php
	$runingTotal = array();
	$i=0;
	$wp_query = new WP_Query();
	$wp_query->query(array(
	'post_type'=>'egg',
	'posts_per_page' => 10,
	// 'paged' => $paged,
));
	if ($wp_query->have_posts()) :  while ($wp_query->have_posts()) :  $wp_query->the_post(); 

	$id = get_the_ID();
	$numEggs = get_post_meta( $id, 'egg_collect');
	if( $numEggs )$runingTotal[] = $numEggs[0];

	?>	

	<div class="day">
		<h2><?php the_title(); ?></h2>
		<?php if( $numEggs ) { ?>Number of Eggs = <?php echo $numEggs[0]; } ?>
		<?php 
		// echo '<pre>'; 
		// print_r($numEggs);
		// echo '</pre>'; 

		?>
	</div>

<?php endwhile; endif; ?>

	<?php $total = array_sum($runingTotal); ?>
	<div class="egg-total">
		<h3><?php echo 'Total = ' . $total; ?></h3>
	</div>

</div>
