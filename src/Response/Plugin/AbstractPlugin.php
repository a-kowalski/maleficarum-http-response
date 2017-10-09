<?php
/**
 * This class is the basis for all response plugin classes
 */
declare (strict_types=1);

namespace Maleficarum\Response\Plugin;

abstract class AbstractPlugin {
	/**
	 * Fetch plugin name.
	 * @return string
	 * @abstract
	 */
	abstract public function getName(): string;


	/**
	 * Get closure plugin
	 * @return \Closure
	 * @abstract
	 */
	abstract public function getClosure(): \Closure;
}