<?php

namespace App\Controller;

use App\Service\Calculate\LiveEventService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiController extends AbstractController
{

    public function __construct(private readonly LiveEventService $liveEventService)
    {
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function  updLiveEventsAction(Request $request): Response
    {
        date_default_timezone_set('Europe/Kyiv');

        $content = $request->getContent();
        $data = json_decode($content, true);

        $liveId = isset($data['liveId']) ? $data['liveId'] : null;
        $appLog = isset($data['fileContent']) ? $data['fileContent'] : null;

        $this->liveEventService->calculateLive($liveId,$appLog);

        return new Response(
            '{}',
            200,
            ['Content-Type' => 'application/json']
        );
    }

}
