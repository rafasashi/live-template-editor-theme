<?php

class LTPLE_Theme_Contact_Form {
	
	var $parent 	= null;
	
	var $response 	= '';
	
    /**
     * Class constructor
     */
    public function __construct($parent) {
		
		$this->parent = $parent;
		
        $this->define_hooks();
    }

    public function controller() {

        if( !empty($_POST['email']) && !empty($_POST['message']) && !empty($_POST['accept']) ) {

            $email      = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING | FILTER_SANITIZE_EMAIL );
			$message   	= filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );
			$accept 	= filter_input( INPUT_POST, 'accept', FILTER_SANITIZE_STRING );
			
			if( !empty( $email) && !empty($message) && !empty($accept) && $accept == 'on' ) {
			
				// Send an email and redirect user to "Thank you" page.
				
				$name = $email;
				
				if($this->send($name,$email,$message)){
					
					$this->response = '<div class="alert alert-success fade in alert-dismissible show"><b>Congratulations</b>, your message was succesfully sent!</div>';
				}
				else{
					
					$this->response = '<div class="alert alert-danger fade in alert-dismissible show"><b>SMTP Error</b>: this message could not be sent... </div>';
				}
			}
			else{
				
				$this->response = '<div class="alert alert-warning fade in alert-dismissible show"><b>Sorry</b>, some fields are empty... </div>';
			}
        }
    }

	function send($name,$email,$message, $extra = array() ){
		
		$name = sanitize_text_field($name);
		
		$email = sanitize_email($email);
		
		$message = sanitize_text_field($message);
		
		if( !empty($name) && !empty($email) && !empty($message) ){
		
			$subject = '[' . get_bloginfo('name') . '] New message from ' . $name;
			
			$content = 'Message from ' . $name . ' ('.$email.')' . PHP_EOL;
			
			$content .= '__________________' . PHP_EOL . PHP_EOL;
			
			$content .= $message . PHP_EOL;
			
			if( !empty($extra) ){
				
				$content .= '__________________' . PHP_EOL . PHP_EOL;
				
				foreach( $extra as $k => $v ){
					
					$content .= $k . ': ' . $v . PHP_EOL;
				}
			}
			
			$headers[]   = 'Reply-To: '. $name.' <'.$email.'>';
			
			$attachments = array();
			
			$to = get_bloginfo('admin_email');

			if( wp_mail($to,$subject,$content,$headers,$attachments) ){
				
				return true;
			}
		}
		
		return false;
	}

    /**
     * Display form
     */
    public function render(){

		?>
				
		<div id="studio-contact" class="container-fluid studio-contact m-auto" style="<?php $this->parent->render('ltple_contact_form_bkg'); ?>">
		
		<?php $this->parent->render('ltple_contact_form_headline'); ?>

		<div class="container col-md-8 m-auto pt-4 pb-5">
				
			<?php
			
			if( !empty($this->response) ){
				
				echo $this->response;
			}
			
			?>
				
			<form method="post" id="contact" action="#contact">
			  
				<div class="studioform-group sfemail">
					<label for="studioControlInput1">Email address</label>
					<input name="email" type="email" class="form-control" id="studioControlInput1" placeholder="name@example.com" required="required">
				</div>
				  
				<div class="studioform-group sfmessage">
					<label for="studioControlTextarea1">Message</label>
					<textarea name="message" class="form-control xcr" id="studioControlTextarea1" rows="3" placeholder="Type your message...."  required="required"></textarea>
				</div>
				
				<div class="form-check scheck mb-3">
				  <input name="accept" type="checkbox" class="skstyle" id="form-check-input" required="required">
				  <label class="form-check-label" for="form-check-input">I agree with <a href="<?php echo $this->parent->render('ltple_contact_form_privacy_url'); ?>">Privacy Policy</a> and <a href="<?php echo $this->parent->render('ltple_contact_form_terms_url'); ?>">Terms</a></label>
				</div>

				<?php $this->parent->render('ltple_contact_form_button'); ?>

			</form>

		</div>
		
		<?php
    }

    /**
     * Define hooks related to plugin
     */
    private function define_hooks() {

        /**
         * Add action to send email
         */
        add_action( 'wp', array( $this, 'controller' ) );

        /**
         * Add shortcode to display form
         */
        add_shortcode( 'ltple-contact-form', array( $this, 'render' ) );
    }
}
