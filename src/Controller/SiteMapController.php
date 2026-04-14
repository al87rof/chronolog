<?php

namespace App\Controller;


use App\Service\SiteMap\SiteMapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SiteMapController extends AbstractController
{

    public function __construct(private readonly SiteMapGenerator $siteMapGenerator){}

    public function siteMapAction(Request $request): Response
    {
        $xml = $this->siteMapGenerator->generateSiteMapXML();

        return new Response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

}
