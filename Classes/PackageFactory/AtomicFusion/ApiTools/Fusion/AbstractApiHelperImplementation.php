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
use TYPO3\TypoScript\Core\Cache\ContentCache;
use TYPO3\TypoScript\TypoScriptObjects\ArrayImplementation;
use PackageFactory\AtomicFusion\ApiTools\Service\Yaml\ServiceInterface as YamlServiceInterface;
use PackageFactory\AtomicFusion\ApiTools\Service\FusionReflectionService;

abstract class AbstractApiHelperImplementation extends ArrayImplementation
{
	use ReflectionTrait;
	use EndpointTrait;

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
	 * Determine wehter a certain subpath is a nested Api helper of the same type
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

	/**
	 * Render a nested Api helper
	 *
	 * @param $key
	 * @return boolean
	 */
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

	/**
	 * Increase the indentation of the given string
	 * TODO: Refactor, move to Utility class
	 *
	 * @param string $yaml
	 * @return string
	 */
	protected function indentOutput($yaml)
	{
		$lines = explode(PHP_EOL, $yaml);


		foreach ($lines as &$line) {
			if (strpos($line, ContentCache::CACHE_SEGMENT_END_TOKEN) !== false) {
				if ($line{0} !== '#') {
					$line = '#' . $line;
				}
				continue;
			}

			if (trim($line)) {
				$line = '  ' . $line;
			}
		}

		return implode(PHP_EOL, $lines);
	}

	/**
	 * Acutual Api helper evaluation
	 *
	 * @return string
	 */
	abstract protected function renderStructure();

	public function evaluate()
	{
		//
		// Endpoint pre-processing
		//
		if ($value = $this->renderEndpointDeflationValue()) {
			return $value;
		}

		//
		// Rendering
		//
		$yaml = PHP_EOL . $this->renderStructure();

		//
		// Endpoint post-processing
		//
		if ($this->endpointShouldPostProcess()) {
			return $this->convertToEndpointFormat($yaml);
		}

		return $yaml;
	}
}
