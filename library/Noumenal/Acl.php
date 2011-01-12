<?php
/**
 * Concrete subclass of  Noumenal_Acl_FacadeAbstract
 *
 * Defines guest, user, admin, and system roles most commonly needed.
 *
 * Usage in Model class or Controller implementing
 * Noumenal_Acl_ResourceInterface:
 *
 *     $acl = new Noumenal_Acl;
 *     $acl->isAllowed($this, null);
 *
 * ('null' implies all priviledges as per Zend_Acl)
 */
class Noumenal_Acl extends Noumenal_Acl_FacadeAbstract
{
	protected $_roles = array(
		'guest' => null,
		'user'  => 'guest',
		'admin' => 'user',
		'system'=> 'admin'
	);
}