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

/**
 * Use PHPs native yaml_* functions, if the extension is installed
 */
class NativeYamlService implements ServiceInterface
{
	use CheckJsonSerializableTrait;

	/**
	 * @inheritDoc
	 */
	public function parse($yamlString)
	{
		$this->checkForYamlExtension();

		try {
			return \yaml_parse($yamlString);
		} catch (\Exception $e) {
			throw new Exception('Could not parse string', 1475310009);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function stringify($jsonSerializable)
	{
		$this->checkForYamlExtension();
		$this->checkJsonSerializable($jsonSerializable);

		try {
			return \yaml_emit($jsonSerializable);
		} catch (\Exception $e) {
			throw new Exception('Failed to convert value', 1475310434);
		}
	}

	/**
	 * Check if PHPs yaml extension is present
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function checkForYamlExtension()
	{
		if (!\extension_loaded('yaml')) {
			throw new Exception('NativeYamlService needs PHP yaml extension to be active', 1475310572);
		}
	}
}
