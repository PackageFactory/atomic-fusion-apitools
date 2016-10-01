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
 * A Fusion Object to represent arbitrary arrays
 */
class ArrayImplementation extends AbstractApiHelperImplementation
{
	public function evaluate()
	{
		$keys = $this->sortNestedTypoScriptKeys();
		$collectedItems = [];
		$result = '';

		foreach ($keys as $key) {
			if ($this->isNestedApiHelper($key)) {
				$result .= $this->yamlService->stringify($collectedItems);
				$result .= $this->renderNestedApiHelper($key);

				$collectedItems = [];
				continue;
			}

			$collectedItems[] = $this->tsRuntime->render(
                sprintf('%s/%s', $this->path, $key)
            );
		}

		if (count($collectedItems)) {
			$result .= $this->yamlService->stringify($collectedItems);
		}

		return $result;
	}
}
