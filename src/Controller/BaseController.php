<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class BaseController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'cache' => AdapterInterface::class,
        ]);
    }

    protected function getCache(): AdapterInterface
    {
        return $this->container->get('cache');
    }

    protected function getEm(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }
}
