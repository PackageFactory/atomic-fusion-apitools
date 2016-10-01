<?php
namespace PackageFactory\AtomicFusion\ApiTools\Fusion;

/**
 * This file is part of the PackageFactory.AtomicFusion.ApiTools package
 *
 * (c) 2016 Wilhelm Behncke <wilhelm.behncke@googlemail.com>
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use PackageFactory\AtomicFusion\ApiTools\Service\FusionReflectionService;

trait ReflectionTrait
{
	/**
	 * @Flow\Inject
	 * @var FusionReflectionService
	 */
	protected $fusionReflectionService;

	/**
	 * Check if a certain protoype is known to the current runtime
	 *
	 * @param  string $prototypeName
	 * @return boolean
	 */
	protected function prototypeExists($prototypeName)
	{
		return $this->fusionReflectionService->prototypeExists($this->tsRuntime, $prototypeName);
	}

	/**
	 * Check if a certain protoype inherits from another prototype
	 *
	 * @param  string $prototypeName
	 * @param  string $parentPrototypeName
	 * @return boolean
	 */
	protected function prototypeInheritsFrom($prototypeName, $parentPrototypeName)
	{
		return $this->fusionReflectionService->prototypeInheritsFrom(
			$this->tsRuntime,
			$prototypeName, 
			$parentPrototypeName
		);
	}
}
