<div class="grey-container shortcut-wrapper">

    <?php

    echo anchor('user_reports/messages','
					<span class="shortcut-icon">
						<i class="fa fa-comments"></i>
					</span>
						   <span class="text"> Messages</span>',array('class'=>'shortcut-link'))

    ?>



    <?php

    echo anchor('user_reports/payments','
					<span class="shortcut-icon">
						<i class="fa fa-credit-card"></i>
					</span>
						   <span class="text"> Payments</span>',array('class'=>'shortcut-link'))

    ?>

</div><!-- /grey-container -->