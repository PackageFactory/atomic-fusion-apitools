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
use TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject;
use PackageFactory\AtomicFusion\ApiTools\Service\Yaml\ServiceInterface as YamlServiceInterface;

abstract class AbstractApiHelperImplementation extends AbstractTypoScriptObject
{
	/**
	 * @Flow\Inject
	 * @var YamlServiceInterface
	 */
	protected $yamlService;

	/**
	 * Render a data structure from the given fusion configuration
	 *
	 * @return mixed
	 */
	abstract public function renderStructure();

	/**
	 * Each Api Helper must provide a method `renderStructure`, which's return value will be
	 * yaml-parsed and properly indented in the `evaluate` method.
	 *
	 * @return string
	 */
	public function evaluate()
	{
		$structure = $this->renderStructure();
		$yaml = $this->yamlService->stringify($structure);

		//
		// TODO: Handle indentation
		//

		return $yaml;
	}
}
