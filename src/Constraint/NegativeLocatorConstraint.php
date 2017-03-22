<?php

namespace Openbuildings\PHPUnitSpiderling\Constraint;

use Openbuildings\Spiderling\Exception_Found;

class NegativeLocatorConstraint extends \PHPUnit\Framework\Constraint\Constraint {

	protected $_type;
	protected $_selector;
	protected $_filters;

	function __construct($type, $selector, array $filters = array())
	{
		$this->_type = $type;
		$this->_selector = $selector;
		$this->_filters = $filters;
	}

	protected function matches($other)
	{
		try
		{
			$other->not_present(array($this->_type, $this->_selector, $this->_filters));
			return TRUE;
		}
		catch (Exception_Found $excption)
		{
			return FALSE;
		}
	}

	public function failureDescription($other)
	{
		if ($other->is_root())
		{
			$node_string = 'HTML page';
		}
		else
		{
			$node_string = $other->tag_name();

			if ($id = $other->attribute('id'))
			{
				$node_string .= '#'.$id;
			}

			if ($class = $other->attribute('class'))
			{
				$node_string .= '.'.join('.', explode(' ', $class));
			}
		}
		return "$node_string ".$this->toString();
	}

	/**
	 * Returns a string representation of the constraint.
	 *
	 * @return string
	 */
	public function toString()
	{
		return "does not have '{$this->_type}' selector '{$this->_selector}', filter ".json_encode($this->_filters);
	}
}
