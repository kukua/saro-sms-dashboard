<div class="grey-container shortcut-wrapper">


    <?php

    ?>

    <?php

    echo anchor('user_messages/send_bulk_sms','
					<span class="shortcut-icon">
						<i class="fa fa-comments-o"></i>
					</span>
						   <span class="text">Send Bulk SMS </span>',array('class'=>'shortcut-link'))

    ?>

    <?php

    echo anchor('user_messages/send_personalised_sms','
					<span class="shortcut-icon">
						<i class="fa fa-comment-o"></i>
					</span>
						   <span class="text">Send Personalised </span>',array('class'=>'shortcut-link'))

    ?>

    <?php

    $messages = 0;
    echo anchor('user_messages/sent_messages','
					<span class="shortcut-icon">
						<i class="fa fa-list"></i>'.($messages > 0?'<span class="shortcut-alert">'.number_format($messages).'</span>':'').'</span>
						   <span class="text">Sent Messages </span>',array('class'=>'shortcut-link'))

    ?>
 <?php
    $rst = $this->db->select('count(*) as counts')->from('sentitems')->where(array('status' => 0,'sender'=>$this->session->userdata('id')))->get()->result()
    ;
    ?>


    <?php
    echo anchor('user_messages/scheduled_messages', '<span class="shortcut-icon">
						<i class="fa fa-clock-o"></i>
						<span class="shortcut-alert">
							' . number_format($rst[0]->counts) . '
						</span>
					</span>
        <span class="text">SMS Queue</span>', array('class' => 'shortcut-link'));
    ?>


</div><!-- /grey-container -->