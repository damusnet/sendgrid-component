Sendgrid CakePHP component
==========================

Copyright 2011, Damien Varron
Licensed under The MIT License
Redistributions of files must retain the above copyright notice.


Version
-------

Written for CakePHP 1.3+

Configuration
-------------

Add the following lines to your core configuration file with your own credentials:

	Configure::write('Sendgrid.username', 'your@email.com');
	Configure::write('Sendgrid.password', 'yourpassword');

Usage
-----

This component extends the base CakePHP email component, and works virtually the same.

Place the sendgrid.php file in your app/controllers/components/ folder.

In your controller, make sure the component is available:

	public $components = array('Sendgrid');   

Then, simply send messages like this:

	$this->Sendgrid->delivery = 'sendgrid';
	$this->Sendgrid->from = 'sender@domain.com';
	$this->Sendgrid->to = 'recipient@domain.com';
	$this->Sendgrid->subject = 'this is the subject';
	$messageBody = 'this is the message body';
	$this->Sendgrid->send($messageBody);

The syntax of all parameters is the same as the default CakePHP email component:

	http://book.cakephp.org/view/1283/Email

For more information, see the Sendgrid API documentation:

	http://sendgrid.com/documentation/display/api/WebMail

Debugging
--------

You can see the response from Sendgrid in the return value when you send a message:

	$result = $this->Sendgrid->send($messageBody);
	$this->log($result, 'debug');
	