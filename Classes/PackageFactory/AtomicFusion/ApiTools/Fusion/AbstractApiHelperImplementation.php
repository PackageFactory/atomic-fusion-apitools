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

	protected static $prototypeName = self::ABSTRACT_PROTOTYPE;

	/**
	 * @Flow\Inject
	 * @var YamlServiceInterface
	 */
	protected $yamlService;

	/**
	 * Determine wehter a certain subpath is a nested Api helper of the syme type
	 *
	 * @param $key
	 * @return boolean
	 */
	protected function isNestedApiHelper($key)
	{
		if (
			isset($this->properties[$key]) &&
			is_array($this->properties[$key]) &&
			!empty($this->properties[$key]['__objectType'])
		) {
			return $this->prototypeInheritsFrom($this->properties[$key]['__objectType'], self::ABSTRACT_PROTOTYPE);
		}

		return (
			isset($this->properties[$key]) &&
			is_array($this->properties[$key]) &&
			empty($this->properties[$key]['__eelExpression']) &&
			empty($this->properties[$key]['__value'])
		);
	}

	protected function renderNestedApiHelper($key)
	{
		$propertyName = static::$prototypeName;

		if (
			isset($this->properties[$key]) &&
			is_array($this->properties[$key]) &&
			!empty($this->properties[$key]['__objectType'])
		) {
			$propertyName = $this->properties[$key]['__objectType'];
		}

		$yaml = $this->tsRuntime->render(
			sprintf('%s/%s<%s>', $this->path, $key, $propertyName)
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
