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

trait CheckJsonSerializableTrait
{
	/**
	 * Check if the input value can safely be converted to a json string
	 * @param mixed $jsonSerializable
	 * @return void
	 * @throws Exception
	 */
	protected function checkJsonSerializable($maybeJsonSerializable)
	{
		if (is_array($maybeJsonSerializable)) {
			return;
		}

		if ($maybeJsonSerializable instanceof \stdClass) {
			return;
		}

		if ($maybeJsonSerializable instanceof \JsonSerializable) {
			return;
		}

		throw new Exception(
			sprintf('Expected \JsonSerializable, instead got: %s', gettype($maybeJsonSerializable)),
			1475310241
		);
	}
}
