;window.hasOwnProperty = function (obj) {return (this[obj]) ? true : false;};
if (!window.hasOwnProperty('IWD')){IWD = {};}
var IWD=IWD||{};
IWD.StockNotification = {
	requestUrl: null,
	currentBlock : null,
	container:null,
	validateEmailMessage:null,

	init: function(){
		$ji(document).on('click', '.request-notice', function(e){
			e.preventDefault();
			IWD.StockNotification.sendRequestNotify($ji(this));
		});
		$ji(document).on('keydown', "input#email_notification", function (e) {
			if (e.which == 13) {
				e.preventDefault();
				IWD.StockNotification.sendRequestNotify($ji(this));
			}
		});

		$ji(document).on('click', '.btn-notify', function(e){
			e.preventDefault();
			$ji('.modal-notify').hide();//hide all
			IWD.StockNotification.currentBlock = $ji(this).data('id');
			$ji('#modal-notify-'+IWD.StockNotification.currentBlock).fadeIn();
		});

		$ji(document).on('click', 'i.close-notification', function(e){
			e.preventDefault();
			$ji(this).closest('.modal-notify').hide();
		});
	},

	sendRequestNotify: function(button){
		IWD.StockNotification.container  = button.closest('.stock-notification');
		var email = IWD.StockNotification.container.find('input[name="email_notification"]').val();
		var product = IWD.StockNotification.container.find('[name="product_id"]').val();
		var parent = IWD.StockNotification.container.find('input[name="parent_id"]').val();

		if (!IWD.StockNotification.validateEmail(email)){
			IWD.StockNotification.container.find('.stock-notification-message').html(IWD.StockNotification.validateEmailMessage).addClass('error');
			return;
		}

		$ji.post(IWD.StockNotification.requestUrl,{"email":email, "id":product,'parent':parent}, function(response){
			IWD.StockNotification.applyError(response);
			IWD.StockNotification.applyResponse(response);
		},'json');
	},

	applyError: function(response){
		if (typeof(response.error)!='undefined' && response.error==true){
			IWD.StockNotification.container.find('.stock-notification-message').html(response.message).removeClass('success').addClass('error');
		}
	},

	applyResponse: function(response){
		if (typeof(response.error)!='undefined' && response.error==false){
			IWD.StockNotification.container.find('.stock-notification-header').hide();
			IWD.StockNotification.container.find('.stock-notification-message').html(response.message).removeClass('error').addClass('success');
			IWD.StockNotification.closeExistModal();
		}
	},

	validateEmail: function(email){
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		return emailPattern.test(email);
	},

	closeExistModal:function(){
		if ($ji('#modal-notify-'+IWD.StockNotification.currentBlock).length){
			setTimeout(function(){
				$ji('#modal-notify-'+IWD.StockNotification.currentBlock).fadeOut();
			}, 2000);
		}
	}
};
if (typeof(jQueryIWD)!="undefined"){
	$ji(document).ready(function(){
		IWD.StockNotification.init();
	});
}else{
	console.log('IWD jQuery undefined');
};