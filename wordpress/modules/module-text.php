<?php
defined('ABSPATH') OR exit('No direct script access allowed');

$columns = get_sub_field('col');
if ( $columns == 1 ) {
	$col_class = 'col';
} elseif ( $columns == 2 ) {
	$col_class = 'col-md-6';
} elseif ( $columns == 3 ) {
	$col_class = 'col-md-4';
} else {
	$col_class = 'col-md-3 col-sm-6';
}
?>
<section class="inside-content">
	<div class="container">
		<div class="row">
			<?php
			for ( $i = 1; $i <= $columns; $i++ ) {
				?>
				<div class="<?php echo $col_class; ?>"><?php echo wpautop(get_sub_field('text_col_'.$i)); ?></div>
				<?php
			}
			?>
		</div>
	</div>
</section>
<?php
