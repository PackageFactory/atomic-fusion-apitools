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

interface ServiceInterface
{
	/**
	 * Parse the given YAML string
	 *
	 * @param string $yamlString
	 * @return array
	 * @throws Exception
	 */
	public function parse($yamlString);

	/**
	 * Converts the given serializable data structure to a yaml string
	 *
	 * @param \JsonSerializable|array|\stdClass $jsonSerializable
	 * @return string
	 * @throws Exception
	 */
	public function stringify($jsonSerializable);
}
