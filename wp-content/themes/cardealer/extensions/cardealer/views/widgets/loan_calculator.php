<?php
if (!defined('ABSPATH')) die('No direct access allowed');

global $post;
$car_price = get_post_meta($post->ID, 'car_price', true);
$show_float_format = TMM::get_option( 'show_float_format', TMM_APP_CARDEALER_PREFIX ) ? '2' : '0';
$thousand_sep = TMM::get_option( 'car_price_thousand_separator', TMM_APP_CARDEALER_PREFIX );
$desimal_sep = $thousand_sep === 'dot' ? ',' : '.';
$thousand_sep = $thousand_sep === 'dot' ? '.' : ',';
$default_amount = !empty($car_price) ? $car_price : $instance['loan_amount'];
$num_format = $instance['num_format'];
$dealer_rate = get_user_meta($post->post_author, 'cardealer_loan_rate', 1);
$default_rate = $dealer_rate !== '' ? $dealer_rate : $instance['interest_rate'];
?>

<div class="widget widget_loan_calculator">

	<div class="boxed-widget">

		<?php if (!empty($instance['title'])): ?>

			<div class="widget-head">
				<h3 class="widget-title icon-calculator"><?php _e($instance['title'], 'cardealer'); ?></h3>
			</div>

		<?php endif; ?>

		<script type="text/javascript">
			var thousand_sep = "<?php echo $desimal_sep?>";
			var desimal_sep = "<?php echo $thousand_sep?>";
			var num_format = "<?php echo $num_format?>";
		</script>

		<form action="/" method="POST" name="myform" id="loan">

			<ul>
				<li><label for="LoanAmount"><?php _e('Amount', 'cardealer')?> (<?php echo TMM_Ext_Car_Dealer::$default_currency['symbol'] ?>)</label></li>
				<li><input  name="LoanAmount" id="LoanAmount" type="text" value="<?php if($num_format === "true") { echo number_format($default_amount, $show_float_format, $desimal_sep, $thousand_sep); } else { echo number_format($default_amount, $show_float_format, $desimal_sep, ''); } ?>" /></li>
				<li><label for="InterestRate"><?php _e('Rate', 'cardealer') ?> (%)</label></li>
				<li><input  name="InterestRate" id="InterestRate" type="text" value="<?php echo $default_rate ?>" /></li>
				<li><label for="NumberOfYears"><?php _e('Term', 'cardealer') ?> (<?php _e('Years', 'cardealer') ?>)</label></li>
				<li><input  name="NumberOfYears" id="NumberOfYears" type="text" value="<?php echo $instance['number_of_years'] ?>" /></li>
				<li><label for="NumberOfPayments"><?php _e('Num of payments', 'cardealer') ?></label></li>
				<li><input disabled="" readonly="readonly" type="text" id="NumberOfPayments" name="NumberOfPayments" /></li>
				<li><label for="MonthlyPayment"><?php _e('Monthly Payment', 'cardealer') ?> (<?php echo TMM_Ext_Car_Dealer::$default_currency['symbol'] ?>)</label></li>
				<li><input disabled="" readonly="readonly" type="text" id="MonthlyPayment" name="MonthlyPayment" /></li>
				<li><button name="cal" class="button orange"><?php _e('Calculate', 'cardealer') ?></button></li>
			</ul>

		</form>

	</div><!--/ .boxed-widget-->

</div><!--/ .widget-->