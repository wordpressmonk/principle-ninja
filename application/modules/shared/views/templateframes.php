<?php foreach ($pages as $key => $frames) : ?>

	<?php
	$frameIDs = array();
	$indivHeights = array();
	$totalHeight = 0;

	foreach ($frames as $frame)
	{
		//frame ID's
		$frameIDs[] = $frame['id'];

		//total height
		$totalHeight += $frame['height'];

		//individual heights
		$indivHeights[] = $frame['height'];

		//original Urls
		$urls[] = $frame['original_url'];
	}

	if ( $totalHeight > 700 ) {
		$totalHeight = 700;
	}

	$pageName = $frames[0]['pageName'];
	$thumb = $frames[0]['thumb'];
	$pageID = $frames[0]['pageID'];

	$frameIDstring = implode("-", $frameIDs);

	$indivHeightsString = implode("-", $indivHeights);

	$originalUrlsString = implode("-", $urls);
	?>

	<li class="templ" data-frames="<?php echo $frameIDstring; ?>" data-heights="<?php echo $indivHeightsString; ?>" data-originalurls="<?php echo $originalUrlsString; ?>" data-name="<?php echo $pageName; ?>" data-pageid="<?php echo $pageID; ?>">
		<?php if ( $thumb == '' ):?>
		<img src="<?php echo base_url('img/nothumb.png');?>">
		<?php else: ?>
		<img src="<?php echo base_url($thumb);?>">
		<?php endif;?>
	</li>
<?php endforeach;?>