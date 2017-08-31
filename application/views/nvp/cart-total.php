<ul id="outmost-cart-dropdown-container" class="nav navbar-nav navbar-right">
	<li id="cart-dropdown-container"  class="<?php echo !empty( $total ) ? 'bg-success' : ''; ?>">
		<a id="cart" href="#" data-toggle="dropdown">
			<span class="caret hidden-xs"></span> <span id="cart-label-my-basket">My Basket:</span> <span id="cart-total"><?php echo ( $total == 0 ? 'Empty' : CURRENCY.number_format( $total, 2 ) );?></span>
		</a>
		<ul class="dropdown-menu-right dropdown-menu" role="menu"></ul>
	</li>
	
</ul>