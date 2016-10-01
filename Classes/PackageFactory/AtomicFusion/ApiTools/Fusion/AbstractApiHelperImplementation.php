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
use TYPO3\TypoScript\TypoScriptObjects\ArrayImplementation;
use PackageFactory\AtomicFusion\ApiTools\Service\Yaml\ServiceInterface as YamlServiceInterface;
use PackageFactory\AtomicFusion\ApiTools\Service\FusionReflectionService;

abstract class AbstractApiHelperImplementation extends ArrayImplementation
{
	use ReflectionTrait;

	/**
	 * An abstract fusion prototype for all Api helpers to inherit from
	 */
	const ABSTRACT_PROTOTYPE = 'PackageFactory.AtomicFusion.ApiTools:Abstract';

	/**
	 * @Flow\Inject
	 * @var YamlServiceInterface
	 */
	protected $yamlService;


	protected function isNestedApiHelper($key)
	{
		if (isset($this->properties[$key]) && isset($this->properties[$key]['__objectType'])) {
			return $this->prototypeInheritsFrom($this->properties[$key]['__objectType'], self::ABSTRACT_PROTOTYPE);
		}

		return false;
	}

	protected function renderNestedApiHelper($key)
	{
		$yaml = $this->tsRuntime->render(
			sprintf('%s/%s', $this->path, $key)
		);

		return $this->indentOutput($yaml);
	}

	protected function indentOutput($yaml)
	{
		$lines = explode(PHP_EOL, $yaml);

		foreach ($lines as &$line) {
			if (trim($line)) {
				$line = '  ' . $line;
			}
		}
		return implode(PHP_EOL, $lines);
	}
}
