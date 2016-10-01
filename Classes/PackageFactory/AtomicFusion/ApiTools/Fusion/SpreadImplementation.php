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
 * A Fusion Object to help spread lists and objects
 */
class SpreadImplementation extends AbstractApiHelperImplementation
{
	/**
	 * Base Prototype name for this fusion object
	 */
	const PROTOTYPE = 'PackageFactory.AtomicFusion.ApiTools:Spread';

	protected static $prototypeName = self::PROTOTYPE;

	protected function validatePathes()
	{
		if (!isset($this->properties['itemRenderer'])) {
			throw new \Exception('You must provide a spread item renderer.', 1475336089);
		}

		if (
			!isset($this->properties['itemRenderer']['__objectType']) ||
			!$this->prototypeInheritsFrom(
				$this->properties['itemRenderer']['__objectType'],
				AbstractApiHelperImplementation::ABSTRACT_PROTOTYPE
			)
		) {
			throw new \Exception('You must use an Api Helper for the spread item renderer.', 1475335953);
		}

		if (
			isset($this->properties['itemRenderer']['__objectType']) &&
			$this->prototypeInheritsFrom(
				$this->properties['itemRenderer']['__objectType'],
				self::PROTOTYPE
			)
		) {
			throw new \Exception('You must not use spread as an item renderer.', 1475335995);
		}
	}

	public function evaluate()
	{
		$this->validatePathes();

		$collection = $this->tsValue('collection');
		$itemName = $this->tsValue('itemName');
		$itemKey = $this->tsValue('itemKey');

		$result = '';
		foreach ($collection as $key => $item) {
			$context = $this->tsRuntime->getCurrentContext();
            $context[$itemName] = $item;

            if ($key !== null) {
                $context[$itemKey] = $key;
            }

			$this->tsRuntime->pushContextArray($context);
			if ($this->tsRuntime->canRender($this->path . '/keyRenderer')) {
				$renderedKey = $this->tsRuntime->render($this->path . '/keyRenderer');
				$result .= rtrim($this->yamlService->stringify([$renderedKey => []]), '{ }' . PHP_EOL);
			} else {
				$result .= '-';
			}
            $result .= PHP_EOL . $this->indentOutput($this->tsRuntime->render($this->path . '/itemRenderer'));
            $this->tsRuntime->popContext();
		}

		return $result;
	}
}
