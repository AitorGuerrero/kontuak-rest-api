<?php

namespace AppBundle\Controller\Exception;

class ControllerNotImplemented extends \Exception
{
    protected $message = 'Controller not implemented';
}