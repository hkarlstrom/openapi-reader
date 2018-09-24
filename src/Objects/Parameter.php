<?php

/**
 * OpenAPI Reader.
 *
 * @see      https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Objects;

class Parameter
{
    public $name;
    public $in;
    public $description;
    public $required;
    public $deprecated;
    public $allowEmptyValue;
    public $style;
    public $explode;
    public $allowReserved;
    public $schema;

    public function __construct(array $args)
    {
        if (!isset($args['name'])) {
            throw new Exception('Parameter name is required.');
        }
        $this->name = $args['name'];
        if (!isset($args['in'])) {
            throw new Exception('Parameter in is required.');
        }
        if (!in_array($args['in'], ['query', 'header', 'path', 'cookie'])) {
            throw new Exception('Parameter in must be one of query|header|path|cookie.');
        }
        $this->in              = $args['in'];
        $this->description     = $args['description']                             ?? null;
        $this->required        = 'path' === $this->in ? true : $args['required']  ?? false;
        $this->deprecated      = $args['deprecated']                              ?? false;
        $this->allowEmptyValue = 'query' === $this->in ? $args['allowEmptyValue'] ?? false : null;
        if (!isset($args['style'])) {
            switch ($this->in) {
                case 'query': $this->style  = 'form'; break;
                case 'path': $this->style   = 'simple'; break;
                case 'header': $this->style = 'simple'; break;
                case 'cookie': $this->style = 'form'; break;
            }
        } else {
            $this->style = $args['style'];
        }
        // When style is form, the default value is true.
        // For all other styles, the default value is false.
        $this->explode       = $args['explode']       ?? 'form' == $this->style;
        $this->allowReserved = $args['allowReserved'] ?? false;
        if (isset($args['schema'])) {
            $this->schema = (object) $args['schema'];
        }
    }
}
