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

/**
 * A Fusion Object to represent arbitrary dictionaries
 */
class DictionaryImplementation extends AbstractApiHelperImplementation
{
	/**
	 * Base Prototype name for this fusion object
	 */
	const PROTOTYPE = 'PackageFactory.AtomicFusion.ApiTools:Dictionary';

	protected static $prototypeName = self::PROTOTYPE;

	public function evaluate()
	{
		$keys = $this->sortNestedTypoScriptKeys();
		$collectedItems = [];
		$result = '';

		foreach ($keys as $key) {
			if ($this->isNestedApiHelper($key)) {
				if (count($collectedItems)) {
					$result .= $this->yamlService->stringify($collectedItems);
				}
				$result .= rtrim($this->yamlService->stringify([$key => []]), '{ }' . PHP_EOL);
				$result .= PHP_EOL . $this->renderNestedApiHelper($key);

				$collectedItems = [];
				continue;
			}

			$collectedItems[$key] = $this->tsRuntime->render(
                sprintf('%s/%s', $this->path, $key)
            );
		}

		if (count($collectedItems)) {
			$result .= $this->yamlService->stringify($collectedItems);
		}

		return $result;
	}
}
