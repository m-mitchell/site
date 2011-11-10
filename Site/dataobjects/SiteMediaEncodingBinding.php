<?php

require_once 'SwatDB/SwatDBDataObject.php';
require_once 'Site/dataobjects/SiteMediaType.php';

/**
 * A media encoding binding object
 *
 * @package   Site
 * @copyright 2011 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 */
class SiteMediaEncodingBinding extends SwatDBDataObject
{
	// {{{ public properties

	/**
	 * Width
	 *
	 * @var integer
	 */
	public $width;

	/**
	 * Height
	 *
	 * @var integer
	 */
	public $height;

	/**
	 * File size in bytes.
	 *
	 * Stored as float to prevent integer overflow.
	 *
	 * @var float
	 */
	public $filesize;

	/**
	 * BOTR key
	 *
	 * @var string
	 */
	public $key;

	/**
	 * Whether or not this encoding has been copied to the CDN
	 *
	 * @var boolean
	 */
	public $on_cdn = false;

	/**
	 * Encoding Id
	 *
	 * This is not an internal property since alternative effiecient methods
	 * are used to load encodings and encoding bindings.
	 *
	 * @var integer
	 */
	public $media_encoding;

	/**
	 * Media Id
	 *
	 * This is not an internal property since alternative effiecient methods
	 * are used to load encodings and encoding bindings.
	 *
	 * @var integer
	 */
	public $media;

	// }}}
	// {{{ private properties

	private static $media_type_cache = array();

	// }}}
	// {{{ public function getHumanFileType()

	public function getHumanFileType()
	{
		switch ($this->media_type->mime_type) {
		case 'video/mp4':       return 'MP4';
		case 'audio/mp4':       return 'MP4';
		case 'audio/mpeg':      return 'MP3';
		default:
			throw new SiteException(sprintf(
				'Unknown mime type %s', $this->media_type->mime_type));
		}
	}

	// }}}
	// {{{ public function getFormattedFileSize()

	public function getFormattedFileSize()
	{
		return SwatString::byteFormat($this->filesize, -1, false, 1);
	}

	// }}}
	// {{{ protected function init()

	protected function init()
	{
		$this->table = 'MediaEncodingBinding';

		$this->registerInternalProperty('media_type',
			SwatDBClassMap::get('SiteMediaType'));
	}

	// }}}
	// {{{ protected function hasSubDataObject()

	protected function hasSubDataObject($key)
	{
		$found = parent::hasSubDataObject($key);

		if ($key === 'media_type' && !$found) {
			$media_type_id = $this->getInternalValue('media_type');

			if ($media_type_id !== null &&
				array_key_exists($media_type_id, self::$media_type_cache)) {
				$this->setSubDataObject('media_type',
					self::$media_type_cache[$media_type_id]);

				$found = true;
			}
		}

		return $found;
	}

	// }}}
	// {{{ protected function setSubDataObject()

	protected function setSubDataObject($name, $value)
	{
		if ($name === 'media_type')
			self::$media_type_cache[$value->id] = $value;

		parent::setSubDataObject($name, $value);
	}

	// }}}
	// {{{ protected function saveInternal()

	/**
	 * Saves this object to the database
	 *
	 * Only modified properties are updated.
	 */
	protected function saveInternal()
	{
		$sql = sprintf(
			'delete from %s where media_encoding = %s and media = %s',
			$this->table,
			$this->db->quote($this->media_encoding, 'integer'),
			$this->db->quote($this->media, 'integer'));

		SwatDB::exec($this->db, $sql);

		parent::saveInternal();
	}

	// }}}
	// {{{ protected function getSerializablePrivateProperties()

	protected function getSerializablePrivateProperties()
	{
		return array_merge(parent::getSerializablePrivateProperties(), array(
			'media_type',
		));
	}

	// }}}
}

?>
