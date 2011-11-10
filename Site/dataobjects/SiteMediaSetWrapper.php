<?php

require_once 'SwatDB/SwatDBRecordsetWrapper.php';
require_once 'Site/dataobjects/SiteMediaSet.php';
require_once 'Site/dataobjects/SiteMediaEncodingWrapper.php';

/**
 * A recordset wrapper class for SiteMediaSet objects
 *
 * @package   Site
 * @copyright 2011 silverorange
 * @see       SiteMediaSet
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 */
class SiteMediaSetWrapper extends SwatDBRecordsetWrapper
{
	// {{{ public function __construct()

	public function __construct($recordset = null)
	{
		parent::__construct($recordset);

		if ($recordset !== null) {
			$this->loadAllSubRecordsets(
				'encodings',
				SwatDBClassMap::get('SiteMediaEncodingWrapper'),
				'MediaEncoding',
				'media_set',
				'',
				'media_set, width desc');
		}
	}

	// }}}
	// {{{ protected function init()

	protected function init()
	{
		parent::init();

		$this->row_wrapper_class =
			SwatDBClassMap::get('SiteMediaSet');

		$this->index_field = 'id';
	}

	// }}}
}

?>
