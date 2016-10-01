<?php
namespace PackageFactory\AtomicFusion\ApiTools\Service\Yaml;

/**
 * This file is part of the PackageFactory.AtomicFusion.ApiTools package
 *
 * (c) 2016 Wilhelm Behncke <wilhelm.behncke@googlemail.com>
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Symfony\Component\Yaml\Yaml;

/**
 * Use Symfonys YAML parser implementation
 */
class SymfonyYamlService implements ServiceInterface
{
	use CheckJsonSerializableTrait;

	/**
	 * @inheritDoc
	 */
	public function parse($yamlString)
	{
		try {
			return Yaml::parse($yamlString);
		} catch (\Exception $e) {
			throw new Exception('Could not parse string', 1475310795);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function stringify($jsonSerializable)
	{
		$this->checkJsonSerializable($jsonSerializable);

		try {
			return Yaml::dump($jsonSerializable);
		} catch (\Exception $e) {
			throw new Exception('Failed to convert value', 1475310803);
		}
	}
}
