<?php if ($ukrcredits_order_status) { ?>
<script>
$(document).ready(function(){

    $("#button-status").click(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/askstatus_pp&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['paymentState'] + '</div>');
							$('#ukrcredits_order_status').html(data['paymentState']);
                            break;
                        case 'FAIL':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }                                 
               }    
        });
        return false;    
    });
	
    $("#button-confirm").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/confirmhold_pp&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-success">Заказ успешно подтвержден!</div>');
							$('#ukrcredits_order_status').html('SUCCESS');
							
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['comment'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
										
										$('textarea[name=\'comment\']').val('');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
							
                            break;
                        case 'FAIL':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }                                 
               }    
        });
        return false;   
	}
    });
    $("#button-cancel").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/cancelhold_pp&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data['state']);
                    switch(data['state']){
                        case 'SUCCESS':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-success">Заказ успешно отменен!</div>');
							$('#ukrcredits_order_status').html('CANCELED');
							
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['comment'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
							
                            break;
                        case 'FAIL':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                        case 'sys_error':
							$('.success, .warning, .alert, .attention').remove();
                            $('.page-header > div').append('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
							break;
                    }                                 
               }    
        });
        return false;    
	}
    });  
	
    $("#button-status-mb").click(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/askstatus_mb&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При обновлении статуса заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);					
					}
               }    
        });
        return false;    
    });
	
    $("#button-cancel-mb").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/cancelhold_mb&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
						
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['comment'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
					}
               }                                   
        });
        return false;    
	}
    });  

    $("#button-confirm-ab").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/confirmhold_ab&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['statusCode'] && data['statusCode'] != 'CONFIRM_IS_OK') {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При подтрерждении заказа произошла ошибка: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
					}
					if (data['statusCode'] && data['statusCode'] == 'CONFIRM_IS_OK') {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
						$('#ukrcredits_order_status').html(data['statusCode'] + ' / ' + data['statusText']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
						 
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['statusText'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
					}
               }                                   
        });
        return false;    
	}
    }); 
	
    $("#button-cancel-ab").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/cancelhold_ab&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['statusCode'] && data['statusCode'] != 'CANCEL_IS_OK') {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При отмене заказа произошла ошибка: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
					}
					if (data['statusCode'] && data['statusCode'] == 'CANCEL_IS_OK') {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
						$('#ukrcredits_order_status').html(data['statusCode'] + ' / ' + data['statusText']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
						 
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['statusText'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {
								//	$('.alert').remove();

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
					}
               }                                   
        });
        return false;    
	}
    }); 	
	
    $("#button-confirm-mb").click(function(){
	if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/confirmhold_mb&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&order_id=<?php echo $order_id; ?>&payment_code=<?php echo $payment_code; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При подтверждении заказа произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['order_id']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Заказ успешно подтвержден!: ' + data['state'] + ' / ' + data['order_sub_state'] + '</div>');
						$('#ukrcredits_order_status').html(data['state'] + ' / ' + data['order_sub_state']);
						$('#button-confirm-mb').remove();
						$('#button-cancel-mb').remove();
						
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['comment'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
					}
               }                                   
        });
        return false;    
	}
    });  	
	
    $("#button-return-mb").click(function(){
		if(confirm('<?php echo $text_confirm; ?>')){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/return_mb&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>&summ=' + $('#returnsumm').val(),
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (data['message']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При возврате произошла ошибка: ' + data['message'] + '</div>');
					}
					if (data['status']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">Возврат успешно произведен: ' + data['status'] + '</div>');
						$('#returnsumm').val('');
							$.ajax({
							<?php if ($version20) { ?>
								url: 'index.php?route=sale/order/api&<?php echo $text_token; ?>=<?php echo $token; ?>&api=api/order/history&order_id=<?php echo $order_id; ?>',
							<?php } else { ?>
								url: '<?php echo $store_url; ?>index.php?route=api/order/history&<?php echo $text_token; ?>=' + token + '&order_id=<?php echo $order_id; ?>',
							<?php } ?>
								type: 'post',
								dataType: 'json',
								data: 'order_status_id=' + data['order_status_id'] + '&notify=1&override=0&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + data['comment'],
								beforeSend: function() {
									$('#button-history').button('loading');
								},
								complete: function() {
									$('#button-history').button('reset');
								},
								success: function(json) {

									if (json['error']) {
										$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}

									if (json['success']) {
										$('#history').load('index.php?route=sale/order/history&<?php echo $text_token; ?>=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

										$('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
									}
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
					}
               }    
        });
        return false;
		}
    });
	
    $("#button-status-ab").click(function(){
        $.ajax({
            type: 'post',
            url: 'index.php?route=payment/ukrcredits/askstatus_ab&<?php echo $text_token; ?>=<?php echo $token; ?>&ukrcredits_order_id=<?php echo $ukrcredits_order_id; ?>',
            dataType: 'json',
			beforeSend: function() {
				$('.success, .warning, .alert, attention').remove();
				$('.page-header > div').append('<div class="alert alert-info"> <?php echo $text_wait; ?></div>');
			},
               success: function(data){
					console.log(data);
					if (!data['messageId']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-warning">При обновлении статуса заказа произошла ошибка: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
					}
					if (data['messageId']) {
						$('.success, .warning, .alert, .attention').remove();
                        $('.page-header > div').append('<div class="alert alert-success">Статус заказа обновлен: ' + data['statusCode'] + ' / ' + data['statusText'] + '</div>');
						$('#ukrcredits_order_status').html(data['statusCode'] + ' / ' + data['statusText']);					
					}
               }    
        });
        return false;    
    });
});    
</script>
<?php } ?>