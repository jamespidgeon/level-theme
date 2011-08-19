<?php
/**
 * @file views-view-list.tpl.php
 * Default simple view template to display a list of rows.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $options['type'] will either be ul or ol.
 * @ingroup views_templates
 */
?>
<?php print $wrapper_prefix; ?>
  <?php if (!empty($title)) : ?>
    <h3><?php print $title; ?></h3>
  <?php endif; ?>
  <?php print $list_type_prefix; ?>
    <?php
    $i = 0;
    foreach ($rows as $id => $row):
    	$alpha = $omega = FALSE;
		if($i == 0) {
			$grid_id = 6;
			$vgrid_id = 2;
		} else if($i == 1) {
			$grid_id = 3;
			$vgrid_id = 2;
		} else if($i == 2) {
			$grid_id = 3;
			$vgrid_id = 2;
		} else if($i >= 3) {
			$grid_id = 3;
			$vgrid_id = 1;
		}
		if($i == 0 || $i == 3 || $i == 7) {
			$alpha = TRUE;
		}
		if($i == 2 || $i == 6 || $i == 10) {
			$omega = TRUE;
		}?>
      <li class="<?php print $classes_array[$id]; ?>">
      	<div class="grid-<?php echo $grid_id; ?> vgrid-<?php echo $vgrid_id; if ($alpha) { echo " alpha "; } if ($omega) { echo " omega "; } ?>">
      		<?php print $row; ?>
      	</div>
      </li>
    <?php $i++; endforeach; ?>
  <?php print $list_type_suffix; ?>
<?php print $wrapper_suffix; ?>
