<?php
declare(strict_types=1);

namespace App\Controller;


class ErrorController extends AppController
{
    public function index()
    {
        $this->render('/Error/index');
    }
}
