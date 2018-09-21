<?php

namespace HKarlstrom\OpenApiReader\Objects;

class Info
{
    public $title;
    public $description;
    public $termsOfService;
    public $contact;
    public $license;
    public $version;

    public function __construct(array $args)
    {
        $this->title   = $args['title'];
        $this->schema  = $args['description']    ?? null;
        $this->example = $args['termsOfService'] ?? null;
        if (isset($args['contact'])) {
            $this->contact = new Contact($args['contact']);
        }
        if (isset($args['license'])) {
            $this->license = new License($args['license']);
        }
        $this->version = $args['version'];
    }
}
