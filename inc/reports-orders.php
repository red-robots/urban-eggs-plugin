<div class="wrap">
	<h1>Reports</h1>


<?php
	
	$runingTotalSales = array();
	$runingTotalCredit = array();
	$runingTotalOwed = array();
	$i=0;
	$wp_query = new WP_Query();
	$wp_query->query(array(
	'post_type'=>'order',
	'posts_per_page' => 10,
	// 'paged' => $paged,
));
	if ($wp_query->have_posts()) :  while ($wp_query->have_posts()) :  $wp_query->the_post(); 

	$id = get_the_ID();
	$orderDate = get_post_meta( $id, 'order_date');
	$orderName = get_post_meta( $id, 'order_name');
	$orderPhone = get_post_meta( $id, 'order_phone');
	$orderEmail = get_post_meta( $id, 'order_email');
	$orderSelect = get_post_meta( $id, 'order_select');
	$orderPaid = get_post_meta( $id, 'order_pay');
	$orderCredit = get_post_meta( $id, 'order_credit');
	$orderOwe = get_post_meta( $id, 'order_owe');
	$orderNotes = get_post_meta( $id, 'order_notes');

	if( $orderPaid )$runingTotalSales[] = $orderPaid[0];
	if( $orderCredit )$runingTotalCredit[] = $orderCredit[0];
	if( $orderOwe )$runingTotalOwed[] = $orderOwe[0];

	?>	

	<div class="day">
		<h2><?php the_title(); ?></h2>
		<?php 

		if( $orderName ) { echo 'Name: ' . $orderName[0] . '<br>'; } 
		if( $orderPhone ) { echo 'Phone: ' . $orderPhone[0] . '<br>'; }
		if( $orderEmail ) { echo 'Email: ' . $orderEmail[0] . '<br>'; }
		if( $orderSelect ) { echo 'Eggs Ordered: ' . $orderSelect[0] . '<br>'; }
		if( $orderPaid ) { ?>
			<div class="the-sale">
				<?php echo 'Paid: $' . $orderPaid[0] . '<br>';  ?>
			</div>
			<?php }
		if( $orderCredit ) { echo 'Credit: $' . $orderCredit[0] . '<br>'; }
		if( $orderOwe ) { echo 'Owes: $' . $orderOwe[0] . '<br>'; }
		if( $orderNotes ) { echo 'Notes: ' . $orderNotes[0] . '<br>'; }
		//if( $numEggs ) { echo 'Number of Eggs = ' . $numEggs[0]; }

		?>
		<?php 
		// echo '<pre>'; 
		// print_r($numEggs);
		// echo '</pre>'; 

		?>
	</div>

<?php endwhile; endif; ?>

	<?php 

	$totalSales = array_sum($runingTotalSales); 
	$totalCredit = array_sum($runingTotalCredit); 
	$totalOwed = array_sum($runingTotalOwed); 

	?>
	<div class="egg-total">
		<h3><?php echo 'Total Sales = $' . $totalSales; ?></h3>
		<h3><?php echo 'Total Credit = $' . $totalCredit; ?></h3>
		<h3><?php echo 'Total Owed = $' . $totalOwed; ?></h3>
	</div>

</div>
