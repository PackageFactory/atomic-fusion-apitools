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

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\Core\Runtime;

/**
 * @Flow\Scope("singleton")
 */
class FusionReflectionService
{
	/**
	 * @var \SplObjectStorage
	 */
	protected $fusionRuntimeMemoryCache;

	public function __construct()
	{
		$this->fusionRuntimeMemoryCache = new \SplObjectStorage();
	}

	/**
	 * Check if a certain protoype is known to the given runtime
	 *
	 * @param  Runtime $runtime
	 * @param  string $prototypeName
	 * @return boolean
	 */
	public function prototypeExists(Runtime $runtime, $prototypeName)
	{
		$fusionConfiguration = $this->getFusionConfigurationFromRuntime($runtime);

		return isset($fusionConfiguration['__prototypes']) &&
			isset($fusionConfiguration['__prototypes'][$prototypeName]);
	}

	/**
	 * Check if a certain protoype inherits from another prototype
	 *
	 * @param  Runtime $runtime
	 * @param  string $prototypeName
	 * @param  string $parentPrototypeName
	 * @return boolean
	 */
	public function prototypeInheritsFrom(Runtime $runtime, $prototypeName, $parentPrototypeName)
	{
		if (!$this->prototypeExists($runtime, $prototypeName) ||
			!$this->prototypeExists($runtime, $parentPrototypeName)) {
			return false;
		}

		if ($prototypeName === $parentPrototypeName) {
			return true;
		}

		$fusionConfiguration = $this->getFusionConfigurationFromRuntime($runtime);

		return in_array(
			$parentPrototypeName,
			$fusionConfiguration['__prototypes'][$prototypeName]['__prototypeChain']
		);
	}

	/**
	 * (Hacky) Helper function, to retrieve that precious fusion configuration
	 *
	 * @param  Runtime $runtime
	 * @return array
	 */
	protected function getFusionConfigurationFromRuntime(Runtime $runtime)
	{
		if (!$this->fusionRuntimeMemoryCache->contains($runtime)) {
			$runtimeReflection = new \ReflectionObject($runtime);
			$fusionConfigurationReflection = $runtimeReflection->getProperty('typoScriptConfiguration');

			$fusionConfigurationReflection->setAccessible(true);
			$fusionConfiguration = $fusionConfigurationReflection->getValue($runtime);
			$fusionConfigurationReflection->setAccessible(false);

			$this->fusionRuntimeMemoryCache->attach($runtime, $fusionConfiguration);
		}

		return $this->fusionRuntimeMemoryCache[ $runtime ];
	}
}
