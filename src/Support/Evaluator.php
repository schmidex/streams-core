<?php

namespace Streams\Core\Support;

/**
 * Class Evaluator
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class Evaluator
{

    /**
     * Evaluate a target entity with arguments.
     *
     * @todo data() -> data_get(), confirm
     *
     * @param $target
     * @param array $arguments
     * @return mixed
     */
    public function evaluate($target, array $arguments = [])
    {
        /*
         * If the target is an instance of Closure then
         * call through the IoC it with the arguments.
         */
        if ($target instanceof \Closure) {
            return app()->call($target, $arguments);
        }

        /*
         * If the target is an array then evaluate
         * each of it's values.
         */
        if (is_array($target)) {
            foreach ($target as &$value) {
                $value = $this->evaluate($value, $arguments);
            }
        }

        /*
         * if the target is a string and is in a traversable
         * format then traverse the target using the arguments.
         */
        if (is_string($target) && !isset($arguments[$target]) && $this->isTraversable($target)) {
            $target = data_get($arguments, $target, $target);
        }

        return $target;
    }

    /**
     * Check if a string is in a traversable format.
     *
     * @param  $target
     * @return bool
     */
    public function isTraversable($target)
    {
        return (!preg_match('/[^a-z._]/', $target));
    }
}
