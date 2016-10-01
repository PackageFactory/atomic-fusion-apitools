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

trait EndpointTrait
{
	/**
	 * @var array
	 */
	protected $endpointMetaData;

	/**
	 * Store the endpoint metadata in memory
	 *
	 * @return array
	 */
	protected function getEndpointMetadata()
	{
		if (!$this->endpointMetaData) {
			$this->endpointMetaData = $this->tsRuntime->render(
				sprintf('%s/__meta/endpoint<TYPO3.TypoScript:RawArray>', $this->path)
			);
		}

		return $this->endpointMetaData;
	}

	/**
	 * Determine, whether this Api helper can be considered an endpoint
	 *
	 * @return boolean
	 */
	protected function isEndpoint()
	{
		$endpointMetaData = $this->getEndpointMetadata();
		return isset($endpointMetaData['active']) && $endpointMetaData['active'] === true;
	}

	/**
	 * Render the configured deflation value of this endpoint
	 *
	 * @return string
	 */
	protected function renderEndpointDeflationValue()
	{
		if ($this->isEndpoint()) {
			$endpointMetaData = $this->getEndpointMetadata();

			if (isset($endpointMetaData['deflate'])) {
				switch ($endpointMetaData['deflate']) {
					case 'object':
					return '{ }';

					case 'array':
					return '[ ]';

					default:
					return;
				}
			}
		}
	}

	/**
	 * Determine, whether this endpoint requires post-processing
	 *
	 * @return boolean
	 */
	protected function endpointShouldPostProcess()
	{
		return $this->isEndpoint() && $this->tsRuntime->canRender(sprintf('%s/__meta/endpoint/format', $this->path));
	}

	/**
	 * Convert the value of this Api Helper to the configured endpoint Format
	 *
	 * @param string $value
	 * @return mixed
	 */
	protected function convertToEndpointFormat($value)
	{
		$endpointMetaData = $this->getEndpointMetadata();
		$context = $this->tsRuntime->getCurrentContext();

		$context['value'] = $this->yamlService->parse($value);

		$this->tsRuntime->pushContextArray($context);
		$result = $this->tsRuntime->render(sprintf('%s/__meta/endpoint/format', $this->path));
		$this->tsRuntime->popContext();

		return $result;
	}
}
