<?php

namespace Streams\Core\Field\Workflows;

use Illuminate\Support\Arr;
use Streams\Core\Field\Field;
use Streams\Core\Support\Workflow;
use Streams\Core\Field\FieldCollection;

/**
 * Class BuildFields
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class BuildFields extends Workflow
{

    protected $steps = [
        'normalize' => BuildFields::class . '@normalize',
        'expand' => BuildFields::class . '@expand',
        'make' => BuildFields::class . '@make',
    ];

    public function normalize($workflow)
    {
        $fields = $workflow->fields;

        foreach ($fields as $handle => &$input) {
            
            if (is_string($input)) {
                $input = [
                    'type' => $input,
                ];
            }

            if (!isset($input['handle'])) {
                $input['handle'] = $handle;
            }

            $input['rules'] = array_map(function ($rules) {

                if (is_string($rules)) {
                    return explode('|', $rules);
                }
    
                return $rules;
            }, Arr::get($input, 'rules', []));
        }
        
        $workflow->fields = $fields;
    }
    
    public function expand($workflow)
    {
        $fields = $workflow->fields;

        foreach ($fields as &$input) {
            
            if (strpos($input['type'], '|')) {
                list($input['type'], $input['input']) = explode('|', $input['type']);
            } else {
                $input['input'] = $input['type'];
            }
        }
        
        $workflow->fields = $fields;
    }

    public function make($workflow)
    {
        $collection = new FieldCollection();

        foreach ($workflow->fields as $field) {

            $field = new Field($field);

            $collection->put($field->handle, $field);
        }

        $workflow->fields = $collection;
    }
}