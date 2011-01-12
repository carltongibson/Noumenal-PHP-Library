<?php
/**
 * Replacement Mail Transport for Easy Testing.
 *
 * Initialise in bootstrap if in development:
 *
 * 	    protected function _initMailTransport()
 *	    {
 *		    if (APPLICATION_ENV === 'development') {
 *			    Zend_Mail::setDefaultTransport(
 *			        new Noumenal_Mail_Transport_TestBed(
 *			            APPLICATION_PATH . '/../tmp/'
 *			        )
 *			    );
 *	    }
 *
 */
class Noumenal_Mail_Transport_TestBed extends Zend_Mail_Transport_Abstract
{
    private $_dir;

	public function __construct($path='/tmp/') {
	    $this->_dir = $path;
	}

	protected function _sendMail()
	{
			$dir  = $this->_dir;
			$file = 'email.'.date('YmdGis');

			$contents = $this->header .
						$this->body;

			file_put_contents($dir.$file, $contents);
	}
}