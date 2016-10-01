<?php
namespace PackageFactory\AtomicFusion\ApiTools\Service;

/**
 * This file is part of the PackageFactory.AtomicFusion.ApiTools package
 *
 * (c) 2016 Wilhelm Behncke <wilhelm.behncke@googlemail.com>
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use PackageFactory\AtomicFusion\ApiTools\Fusion\AbstractApiHelperImplementation;

/**
 * Runtime information about the current position in the rendering tree
 */
class ApiHelperContext
{
	/**
	 * @var integer
	 */
	protected $depth;

	/**
	 * @var AbstractApiHelperImplementation
	 */
	protected $surroundingApiHelper = null;

	/**
	 * Constructor
	 *
	 * @param integer $depth
	 * @param AbstractApiHelperImplementation $surroundingApiHelper
	 */
	public function __construct($depth = 0, AbstractApiHelperImplementation $surroundingApiHelper = null)
	{
		$this->depth = $depth;
		$this->surroundingApiHelper = $surroundingApiHelper;
	}

	/**
	 * Get the depth
	 *
	 * @return integer
	 */
	public function getDepth()
	{
		return $this->depth;
	}

	/**
	 * Get the surrounding Api Helper instance
	 *
	 * @return AbstractApiHelperImplementation
	 */
	public function getSurroundingApiHelper()
	{
		return $this->surroundingApiHelper;
	}

	/**
	 * (Immutably) Set the surrounding Api Helper instance, while also incrementing the depth.
	 *
	 * @param AbstractApiHelperImplementation $surroundingApiHelper
	 * @return ApiHelperContext
	 */
	public function setSurroundingApiHelper(AbstractApiHelperImplementation $surroundingApiHelper)
	{
		return new static($this->depth + 1, $surroundingApiHelper);
	}
}
