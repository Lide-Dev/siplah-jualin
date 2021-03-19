<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "midtrans"): ?>
<script type="text/javascript"
            src="<?php echo ($this->payment_settings->midtrans_mode == 'sandbox') ? 'https://app.sandbox.midtrans.com/snap/snap.js' : 'https://app.midtrans.com/snap/snap.js'; ?>"
            data-client-key="<?php echo $this->payment_settings->midtrans_client_id; ?>"></script>
<?php $attributes = array('id' => 'formmidtrans'); echo form_open('midtrans-payment-post', $attributes); ?>
	<button id="pay-button" class="btn btn-lg btn-custom btn-place-order float-right"><?php echo trans("place_order") ?></button>
<?php echo form_close(); ?>
<script type="text/javascript">

$('#pay-button').click(function (event) {
	event.preventDefault();
	$(this).attr("disabled", "disabled");
	$.ajax({
		url: 'token_midtrans',
		cache: false,
		data: "",
		success: function(data) {
			//location = data;

			console.log('token = '+data);

			var resultType = document.getElementById('result-type');
			var resultData = document.getElementById('result-data');

			function changeResult(type,data){
				$("#result-type").val(type);
				$("#result-data").val(JSON.stringify(data));
				//resultType.innerHTML = type;
				//resultData.innerHTML = JSON.stringify(data);
			}

			snap.pay(data, {

				onSuccess: function(result){
					changeResult('success', result);
					console.log(result.status_message);
					console.log(result);
					console.log("a");
					$("#formmidtrans").submit();
				},
				onPending: function(result){
					changeResult('pending', result);
					console.log(result.status_message);
					console.log("b");
					$("#formmidtrans").submit();
				},
				onError: function(result){
					changeResult('error', result);
					console.log(result.status_message);
					console.log("c");
					$("#formmidtrans").submit();
				}
			});
		}
	});
});
</script>
<?php endif; ?>