<?php require_once 'header-w-bar.php'; ?>

<?php
if( !empty( $products ) && count( $products ) > 0 )
	$model_type = '- '.$products[ 0 ][ 'model_type' ];
else
	$model_type = '';
?>

<div class="content-section">
<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500">Quick search <?php echo $model_type; ?></h1>

<?php if( !empty( $products ) && is_array( $products ) ): ?>

<ul id="quick-search-result-wrapper">

<?php require_once 'quick-search-result-ajax.php'; ?>

</ul>

<div class="centered"><?php echo $pagination; ?></div>

<?php endif; ?>

</div>

<?php require_once 'footer.php';