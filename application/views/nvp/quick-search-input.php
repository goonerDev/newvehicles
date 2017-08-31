<div>

	<h3 id="quick-search-header"><l class="fa fa-car"></l> Quick search</h3>

	<form id="quick-search-form" action="<?php echo base_url(); ?>quick-search">

<!--			
Manufactures within the dropdown list
-->
		<div class="spacer">

			<select id="manufacture" name="manufacture" class="selectpicker form-control"">

				<option value="" selected="selected">Manufacture</option>

			<?php foreach( $manufactures as $m ): ?>
				<option value="<?php echo misc::urlencode( $m[ 'manufacture' ] ); ?>"><?php echo ucwords( strtolower( $m[ 'manufacture' ] ) ); ?></option>
			<?php endforeach; ?>

			</select>

		</div>
<!--
Models within the dropdown list
-->
		<div class="spacer">

			<select id="model" name="model" class="selectpicker form-control">

				<option value="" selected="selected">Model</option>

			</select>

		</div>
<!--
Years within the dropdown list
-->
		<div class="spacer">

			<select id="year" name="year" class="selectpicker form-control">

				<option value="" selected="selected">Year</option>

			</select>

		</div>
<!--
Search button
-->
		<p align="center">

			<input type="submit" value="Find Parts" class="btn btn-primary"/>

		</p>

	</form>
</div>