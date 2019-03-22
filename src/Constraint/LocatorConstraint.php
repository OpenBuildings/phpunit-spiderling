<?php

namespace Openbuildings\PHPUnitSpiderling\Constraint;

use Openbuildings\Spiderling\Exception_Notfound;
use PHPUnit\Framework\Constraint\Constraint;

class LocatorConstraint extends Constraint
{
    protected $_type;
    protected $_selector;
    protected $_filters;

    public function __construct($type, $selector, array $filters = [])
    {
        $this->_type = $type;
        $this->_selector = $selector;
        $this->_filters = $filters;

        parent::__construct();
    }

    protected function matches($other): bool
    {
        try {
            $other->find([$this->_type, $this->_selector, $this->_filters]);

            return true;
        } catch (Exception_Notfound $excption) {
            return false;
        }
    }

    protected function failureDescription($other): string
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
     */
    public function toString(): string
    {
        return "has '{$this->_type}' selector '{$this->_selector}', filter ".json_encode($this->_filters);
    }
}
