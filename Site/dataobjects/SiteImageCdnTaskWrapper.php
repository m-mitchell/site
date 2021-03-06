<?php

require_once 'SwatDB/SwatDBClassMap.php';
require_once 'SwatDB/SwatDBRecordsetWrapper.php';
require_once 'Site/dataobjects/SiteCdnTaskWrapper.php';
require_once 'Site/dataobjects/SiteImageCdnTask.php';

/**
 * A recordset wrapper class for SiteImageCdnTask objects
 *
 * @package   Site
 * @copyright 2010-2016 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @see       SiteImageCdnTask
 */
class SiteImageCdnTaskWrapper extends SiteCdnTaskWrapper
{
	// {{{ protected function init()

	protected function init()
	{
		parent::init();

		$this->row_wrapper_class = SwatDBClassMap::get('SiteImageCdnTask');
	}

	// }}}
}

?>
