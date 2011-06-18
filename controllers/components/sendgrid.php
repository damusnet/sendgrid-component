<?php
/**
 * Sendgrid Component
 *
 * Copyright 2011, Damien Varron
 * Based on the Postmark Component by Daniel McOrmond
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author	Damien Varron
 * @copyright	Copyright 2011, Damien Varron
 * @version	0.1
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Component', 'Email');

/**
 * SendgridComponent
 *
 * This component is used for sending email messages
 * using the Sendgrid API http://sendgridapp.com/
 *
 */
class SendgridComponent extends EmailComponent {

/**
 * Sendgrid API URI
 *
 * @var string
 * @access public
 */
	var $uri = 'https://sendgrid.com/';

/**
 * Sendgrid API Request URI
 *
 * @var string
 * @access public
 */
	var $request = 'api/mail.send.json';

/**
 * Sendgrid API Username
 *
 * @var string
 * @access public
 */
	var $username = null;

/**
 * Sendgrid API Password
 *
 * @var string
 * @access public
 */
	var $password = null;

/**
 * Sendgrid Tag property
 *
 * @var string
 * @access public
 */
	var $tag = null;

/**
 * Variable that holds Sendgrid connection
 *
 * @var resource
 * @access private
 */
	var $__sendgridConnection = null;

/**
 * Initialize component
 *
 * @param object $controller Instantiating controller
 * @access public
 */
	function initialize(&$controller, $settings = array()) {
		parent::initialize($controller, $settings);
		if (Configure::read('Sendgrid.username') !== null) {
			$this->username = Configure::read('Sendgrid.username');
		}
		if (Configure::read('Sendgrid.password') !== null) {
			$this->password = Configure::read('Sendgrid.password');
		}
	}

/**
 * Sends out email via Sendgrid
 *
 * @return bool Success
 * @access private
 */
	function _sendgrid() {
		App::import('Core', 'HttpSocket');

		// Setup connection
		$this->__sendgridConnection =& new HttpSocket();

		// Construct message
		$message = array();
		
		// From
		$message['from'] = $this->_formatAddress($this->from);
		if (!empty($this->fromname)) {
			$message['fromname'] = $this->fromname;
		}
		
		// To
		if (is_array($this->to)) {
			$message['to'] = implode(', ', array_map(array($this, '_formatAddress'), $this->to));
		} else {
			$message['to'] = $this->_formatAddress($this->to);
			if (!empty($this->toname)) {
				$message['toname'] = $this->toname;
			}
		}

		// Cc
		if (!empty($this->cc)) {
			if (is_array($this->cc)) {
				$message['cc'] = implode(', ', array_map(array($this, '_formatAddress'), $this->cc));
			} else {
				$message['cc'] = $this->_formatAddress($this->cc);
			}
		}

		// Bcc
		if (!empty($this->bcc)) {
			if (is_array($this->bcc)) {
				$message['bcc'] = implode(', ', array_map(array($this, '_formatAddress'), $this->bcc));
			} else {
				$message['bcc'] = $this->_formatAddress($this->bcc);
			}
		}

		// Subject
		$message['subject'] = $this->subject;

		// Tag
		if (!empty($this->tag)) {
			$message['Tag'] = $this->tag;
		}

		// HtmlBody
		if ($this->sendAs === 'html' || $this->sendAs === 'both') {
			$message['html'] = $this->htmlMessage;
		}

		// TextBody
		if ($this->sendAs === 'text' || $this->sendAs === 'both') {
			$message['text'] = strip_tags($this->textMessage);
		}

		// ReplyTo
		if (!empty($this->replyTo)) {
			$message['replyto'] = $this->_formatAddress($this->replyTo);
		}

		// Setup header
		$message['api_user'] = $this->username;
		$message['api_key'] = $this->password;
		

		// Send message
		return json_decode($this->__sendgridConnection->post('https://sendgrid.com/api/mail.send.json', $message), true);
	}
}
?>