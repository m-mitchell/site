<?php

require_once 'Site/SiteApplicationModule.php';
require_once 'Site/exceptions/SiteCookieException.php';

/**
 * Web application module for cookies
 *
 * @package   Site
 * @copyright 2006 silverorange
 */
class SiteCookieModule extends SiteApplicationModule
{
	// {{{ public properties

	public $cookie_prefix;

	// }}}
	// {{{ private properties

	private $salt = '';

	// }}}
	// {{{ public function init()

	/**
	 * Initializes this module
	 */
	public function init()
	{
		$this->cookie_prefix = $this->app->id;
	}

	// }}}
	// {{{ public function setSalt()

	/**
	 * Set salt
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}

	// }}}
	// {{{ public function setCookie()

	/**
	 * Sets a cookie
	 *
	 * @param string $name the name of the cookie to set.
	 * @param mixed $value the value of the cookie.
	 * @param mixed $expiry the expiry date as a UNIX timestamp or a
	                         string parsable by strtotime().
	 * @param string $path the URL path this cookie is valid for.
	 * @param string $domain the domain this cookie is valid for.
	 */
	public function setCookie($name, $value, $expiry = null,
		$path = '/', $domain = null)
	{
		$name = $this->cookie_prefix.'_'.$name;

		if ($expiry === null)
			$expiry = strtotime('+90 days');
		elseif (is_string($expiry))
			$expiry = strtotime($expiry);

		$cookie_value = $this->saltValue($value);

		// TODO: get domain from application
		//if ($domain = null)
		//	$domain = 

		setcookie($name, $cookie_value, $expiry, $path);
		//setcookie($name, $value, $expiry, $path, $domain);
	}

	// }}}
	// {{{ public function removeCookie()

	/**
	 * Remove a cookie
	 *
	 * @param string $name the name of the cookie to set.
	 * @param string $path the URL path this cookie is valid for.
	 * @param string $domain the domain this cookie is valid for.
	 */
	public function removeCookie($name, $path = '/', $domain = null)
	{
		$name = $this->cookie_prefix.'_'.$name;
		$expiry = time() - 3600;

		// TODO: get domain from application
		//if ($domain = null)
		//	$domain = 

		setcookie($name, 0, $expiry, $path);
		//setcookie($name, 0, $expiry, $path, $domain);

		unset($_COOKIE[$name]);
	}

	// }}}
	// {{{ private function &__get()

	/**
	 * Gets a cookie value
	 *
	 * @param string $name the name of the cookie to get.
	 *
	 * @return mixed the value of the cookie. This is returned by reference.
	 */
	private function &__get($name)
	{
		$name = $this->cookie_prefix.'_'.$name;

		if (!isset($_COOKIE[$name]))
			throw new SiteCookieException("Cookie '$name' is not set.");

		$value = $this->unsaltValue($_COOKIE[$name]);
		return $value;
	}

	// }}}
	// {{{ private function saltValue()

	private function saltValue($value)
	{
		$package = array();
		$package['value'] = $value;
		$package['hash'] = $this->getHash($value);
		return serialize($package);
	}

	// }}}
	// {{{ private function unsaltValue()

	private function unsaltValue($value)
	{
		$package = @unserialize($value);

		// serialized value is broken
		if ($package === false && strcmp($value, serialize(false)) != 0)
			throw new SiteCookieException('Could not unserialize cookie.');

		// serialized data is not how we expected
		if (!is_array($package) ||
			!isset($package['value']) ||
			!isset($package['hash']))
				throw new SiteCookieException('Corrupt cookie data.');

		$value = $package['value'];

		// hash does not match, cookie was tampered with
		if ($package['hash'] !== $this->getHash($value))
			throw new SiteCookieException('Invalid cookie hash.');

		return $value;
	}

	// }}}
	// {{{ private function getHash()

	private function getHash($value)
	{
		return md5($this->salt.serialize($value));
	}

	// }}}
	// {{{ private function __isset()

	/**
	 * Checks the existence of a cookie
	 *
	 * @param string $name the name of the cookie to check.
	 */
	private function __isset($name)
	{
		$name = $this->cookie_prefix.'_'.$name;
		return isset($_COOKIE[$name]);
	}

	// }}}
}

?>
