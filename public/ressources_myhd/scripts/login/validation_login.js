jQuery("#btn_login").click(function() {
	 login();
});	

function login(){
	$( '#block_alert_login' ).html( '<p><i class="fa fa-spinner fa-spin" style="color: #529644;font-size: 20px;"></i> Identification en cours, veuillez patienter...</p>');
	var login=$('#input_login').val();
	var pass=$('#input_password').val();
	if (!login || login.length == 0)
			{
				var msg='<div id="block_alert_login" class="alert alert-danger"><button class="close" data-close="alert"></button><span> Veuillez entrer votre identifiant</span></div>';
				$('#block_alert_login').html(msg);					 
			}
			else if (!pass || pass.length == 0)
			{
				var msg='<div id="block_alert_login" class="alert alert-danger"><button class="close" data-close="alert"></button><span> Le mot de passe est obligatoire</span></div>';
				$('#block_alert_login').html(msg);
			}
			else
			{
				// Request
				var url="/ServiceLoginAuth/authenticate";
				var datas="login="+login+"&password="+pass;
				// Send
				$.ajax({
					url: url, 
					type: 'POST',
					data: datas,
					success: function(data)
					{
						if(data.success==true){
							document.location.href ='/'+data.redirect;
						}else{
							//alert("nn");
							var msg='<div id="block_alert_login" class="alert alert-danger"><button class="close" data-close="alert"></button><span>Erreur d\'identifiant ou de mot de passe</span></div>';
							$('#block_alert_login').html(msg);
							//$('#modal_transmission_error').modal('show');;
						}
					}
				});			
			}
}