<?php

namespace OutsideForce\Routing\Filter;

use Cake\Event\Event;
use Cake\Routing\DispatcherFilter;
use Cake\Routing\Exception\MissingControllerException;

/**
 * Class OutsideForceFilter
 * @package OutsideForce\Routing\Filter
 */
class OutsideForceFilter extends DispatcherFilter
{
    /**
     * Priority is set high to allow other filters to be called first.
     *
     * @var int
     */
    protected $_priority = 80;

    /**
     * class short name to be excepted
     *
     * @var array
     */
    protected $_classes = ['AppController'];

    /**
     * constructor
     *
     * @param array $config configuration
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!empty($config['classes'])) {
            $this->_classes = $config['classes'];
            if (!is_array($this->_classes)) {
                $this->_classes = [$this->_classes];
            }
        }
    }

    /**
     * check if controller is included in _classes
     *
     * @param Event $event event
     * @return void
     */
    public function beforeDispatch(Event $event)
    {
        if (!empty($this->_classes) && isset($event->data['controller'])) {
            $class = 'Cake\Controller\Controller';
            $controller = $event->data['controller'];
            $reflect = new \ReflectionClass($controller);
            if (($controller instanceof $class) && in_array($reflect->getShortName(), $this->_classes, true)) {
                throw new MissingControllerException(['class' => $controller->name]);
            }
        }
    }
}
