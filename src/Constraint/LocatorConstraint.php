<?php

namespace Openbuildings\PHPUnitSpiderling\Constraint;

use Openbuildings\Spiderling\Exception_Notfound;

class LocatorConstraint extends \PHPUnit\Framework\Constraint\Constraint
{
    protected $_type;
    protected $_selector;
    protected $_filters;

    public function __construct($type, $selector, array $filters = [])
    {
        $this->_type = $type;
        $this->_selector = $selector;
        $this->_filters = $filters;
    }

    protected function matches($other)
    {
        try {
            $other->find([$this->_type, $this->_selector, $this->_filters]);

            return true;
        } catch (Exception_Notfound $excption) {
            return false;
        }
    }

    public function failureDescription($other)
    {
        if ($other->is_root()) {
            $node_string = 'HTML page';
        } else {
            $node_string = $other->tag_name();

            if ($id = $other->attribute('id')) {
                $node_string .= '#'.$id;
            }

            if ($class = $other->attribute('class')) {
                $node_string .= '.'.implode('.', explode(' ', $class));
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
        return "has '{$this->_type}' selector '{$this->_selector}', filter ".json_encode($this->_filters);
    }
}
