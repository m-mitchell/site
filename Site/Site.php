<?php

/**
 * Container for package wide static methods
 *
 * @package   Site
 * @copyright 2006 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 */
class Site
{
	/**
	 * The gettext domain for Site
	 *
	 * This is used to support multiple locales.
	 */
	const GETTEXT_DOMAIN = 'site';

	/**
	 * Translates a phrase
	 *
	 * This is an alias for {@link Site::gettext()}.
	 *
	 * @param string $message the phrase to be translated.
	 *
	 * @return string the translated phrase.
	 */
	public static function _($message)
	{
		return Site::gettext($message);
	}

	/**
	 * Translates a phrase
	 *
	 * This method relies on the php gettext extension and uses dgettext()
	 * internally.
	 *
	 * @param string $message the phrase to be translated.
	 *
	 * @return string the translated phrase.
	 */
	public static function gettext($message)
	{
		return dgettext(Site::GETTEXT_DOMAIN, $message);
	}

	/**
	 * Translates a plural phrase
	 *
	 * This method should be used when a phrase depends on a number. For
	 * example, use ngettext when translating a dynamic phrase like:
	 *
	 * - "There is 1 new item" for 1 item and
	 * - "There are 2 new items" for 2 or more items.
	 *
	 * This method relies on the php gettext extension and uses dngettext()
	 * internally.
	 *
	 * @param string $singular_message the message to use when the number the
	 *                                  phrase depends on is one.
	 * @param string $plural_message the message to use when the number the
	 *                                phrase depends on is more than one.
	 * @param integer $number the number the phrase depends on.
	 *
	 * @return string the translated phrase.
	 */
	public static function ngettext($singular_message, $plural_message, $number)
	{
		return dngettext(Site::GETTEXT_DOMAIN,
			$singular_message, $plural_message, $number);
	}

	/**
	 * Displays the methods of an object
	 *
	 * This is useful for debugging.
	 *
	 * @param mixed $object the object whose methods are to be displayed.
	 */
	public static function displayMethods($object)
	{
		echo sprintf(Site::_('Methods for class %s:'), get_class($object));
		echo '<ul>';

		foreach (get_class_methods(get_class($object)) as $method_name)
			echo '<li>', $method_name, '</li>';

		echo '</ul>';
	}

	/**
	 * Displays the properties of an object
	 *
	 * This is useful for debugging.
	 *
	 * @param mixed $object the object whose properties are to be displayed.
	 */
	public static function displayProperties($object)
	{
		$class = get_class($object);

		echo sprintf(Site::_('Properties for class %s:'), $class);
		echo '<ul>';

		foreach (get_class_vars($class) as $property_name => $value) {
			$instance_value = $object->$property_name;
			echo '<li>', $property_name, ' = ', $instance_value, '</li>';
		}

		echo '</ul>';
	}
}

/*
 * Define a dummy dngettext() for when gettext is not available.
 */
if (!function_exists("dngettext")) {
	function dngettext($domain, $messageid1, $messageid2, $n)
	{
		if ($n == 1)
			return $messageid1;

		return $messageid2;
    }
}

/*
 * Define a dummy dgettext() for when gettext is not available.
 */
if (!function_exists("dgettext")) {  
	function dgettext($domain, $messageid)
	{
		return $messageid;
	}
}

?>
