<?php
abstract class Noumenal_Acl_FacadeAbstract
{
	protected $_roles;

	protected $_auth;
	protected $_acl;

	public function __construct($auth=null)
	{
		if (is_null($auth)) {
		    $auth = Zend_Auth::getInstance();
		}
		$this->_auth = $auth;


		$acl = new Zend_Acl();
		foreach ($this->_roles as $role => $parent)
		{
		    $acl->addRole(new Zend_Acl_Role($role), $parent);
		}
		// $acl->deny(); // create whitelist. Zend_Acl defaults to this.

		$this->_acl = $acl;
	}

	public function isAllowed(Noumenal_Acl_ResourceInterface $resource, $action=null)
	{
		$acl = $this->_acl;

		// add resource and rules to acl
		if (!$acl->has($resource->getResourceId()) ) {
			$acl->addResource($resource);
			foreach ( $resource->getAccessRules() as $role => $privileges )
			{
			    if (!array_key_exists($role, $this->_roles)) {
			        throw new Noumenal_Acl_ConfigurationException(
			        	sprintf(
			        		'Resource %s provides for role "%s" not given in Acl_Facade.',
			        		$resource->getResourceId(), $role));
			    }

			    $acl->allow($role, $resource, $privileges);
			}
		}

		// check if user has sufficient permissions
		$auth = $this->_auth;
		if ($auth->hasIdentity()) {
		    $user = $auth->getIdentity();
		    $role = $user->getRoleId(); // as defined by Zend_Acl_Role_Interface
		    if (!array_key_exists($role, $this->_roles)) {
			        throw new Noumenal_Acl_ConfigurationException(
			        	sprintf('User declares role "%s" not given in Acl_Facade.'
			        	, $role));
			}
		} else {
			$role = 'guest'; // assumed that app will define catch-all "guest" role.
		}

		$result = $acl->isAllowed($role, $resource, $action);
		if (!$result) {
		    throw new Noumenal_Acl_PermissionsException(
		    	sprintf('Insufficient privileges for "%s" to access %s for %s.',
		    	$role, $resource->getResourceId(), is_null($action) ? 'all actions' : $action));
		}

		return true;
	}
}