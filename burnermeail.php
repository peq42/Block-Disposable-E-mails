
<?php
/*
Plugin Name:  Block Disposable E-mails
Plugin URI:   https://peq42.com
Description:  Block Disposable Burner E-mails from being used in your website's register
Version:      1.0
Author:       WPBeginner
Author URI:   https://peq42.com
License:      GPL3
License URI:  https://github.com/peq42/Block-Disposable-E-mails/blob/main/LICENSE
Text Domain:  peq42
Domain Path:  /bbemails
*/


function email_check($errors, $sanitized_user_login, $user_email) {
	
    //get e-mail domain
	$user_email_domain = explode("@", $user_email)[1];

    //check if its DNS has MX
    if(checkdnsrr($user_email_domain, 'MX')==false){
		$errors->add('email_mismatch', __('Email provider is not allowed.', 'text-domain'));
	}else{
        //grab list of disposable e-mails domains
        $blockedlist=file_get_contents('https://raw.githubusercontent.com/wesbos/burner-email-providers/master/emails.txt');
        //breaks into array
        $blockedlist = explode("\n", $blockedlist);

        $lineCount = count($blockedlist);

        //verify if e-mail domain registered is in that list
        for ($i = 0; $i < $lineCount; $i++) {
            if($user_email_domain==$blockedlist[$i]){
                $errors->add('email_mismatch', __('Email provider is not allowed.', 'text-domain'));
                break;
            }
        }
    }
	
	
	return $errors;
}

add_action('registration_errors', 'email_check',10,3);
?>