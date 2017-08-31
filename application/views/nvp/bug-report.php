<?php require_once 'header.php'; ?>

<div class="content-section">
	<h1 class="fs-22 border-2-1010-1e2a51 padding-25002500 color-171f6a">Bug report</h1>

	<?php if( !empty( $error ) ): ?>
	<div class="alert alert-danger"><?php echo $error; ?></div>
	<?php endif; ?>

	<form action="<?php echo base_url(); ?>bug-report" method="post" class="form-horizontal">
		<div class="form-group">
			<label class="control-label col-sm-4">Issue page: <span class="required-field-marker">*</span></label>
			<div class="col-sm-5">
			<input type="text" class="form-control" id="issue_page" name="issue_page" value="<?php echo misc::esc_attr( $issue_page ); ?>"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Issue: <span class="required-field-marker">*</span></label>
			<div class="col-sm-5">
			<input type="text" class="form-control" id="issue" name="issue" value="<?php echo misc::esc_attr( $issue ); ?>"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Suggested improvements: </label>
			<div class="col-sm-5">
			<textarea class="form-control" id="improvement" name="improvement" rows="10"><?php echo $improvement; ?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Enter the letter you see in the box <span class="required-field-marker">*</span></label>
			<div class="col-sm-5">
			<?php echo $captcha[ 'image' ]; ?>
				<input type="text" name="word" size="15"/>
			</div>
		</div>
		<p align="center">
			<input type="submit" id="contact-send-msg-btn" class="btn btn-success" value="Send"/>
		</p>
	</form>
</div>

<?php require_once 'footer.php';