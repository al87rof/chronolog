<?php

namespace App\Controller;

use App\Entity\Events;
use App\Service\Calculate\EventFilter;
use App\Service\Calculate\EventService;
use App\Service\Calculate\LapCalculator;
use App\Service\Calculate\LiveEventService;
use App\Service\RiderProvider\RiderCollector;
use App\Service\RiderProvider\RiderResultFinder;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


class DefaultController extends AbstractController
{

    public function __construct(
        private readonly EventService $eventService,
        private readonly LiveEventService $liveEventService,
        private  readonly EventFilter $eventFilter,
        private readonly  RiderCollector $riderCollector,
        private  readonly LapCalculator $lapCalculator,
        private  readonly  RiderResultFinder $riderResultFinder,
        private  readonly  CsrfTokenManagerInterface $csrfTokenManager
    )
    {
    }

    public function showExceptionAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger): ?Response
    {
        return $this->notFoundAction();
    }

    /**
     * @return Response|null
     */
    public function notFoundAction(): ?Response
    {
        return $this->render('404/404.html.twig', []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('base/base.html.twig', []);
    }

    public function uploadAction(Request $request)
    {
        $file1 = $request->files->get('file1');
        $file2 = $request->files->get('file2');

        $eventName = $request->get('eventName');
        $eventImage = $request->files->get('eventImage');

        if ($eventImage instanceof UploadedFile) {
            $eventImage->move(
                "files",
                $eventImage->getClientOriginalName()
            );
        }


        if ($file2 instanceof UploadedFile) {

            $file1->move(
                "files",
                $file1->getClientOriginalName()
            );

            $file2->move(
                "files",
                $file2->getClientOriginalName()
            );

            $result = $this->eventService->processRaceResults('files/' . $file2->getClientOriginalName(), 'files/' . $file1->getClientOriginalName());

            $eventId = $this->eventService->save(
                'files/' . $file1->getClientOriginalName(),
                'files/' . $file2->getClientOriginalName(),
                'files/' . $eventImage->getClientOriginalName(),
                $eventName,
                $result
            );

            unlink('files/' . $file1->getClientOriginalName());
            unlink('files/' . $file2->getClientOriginalName());

            if ($eventId) {
                return $this->redirectToRoute('result', ['id' => $eventId]);
            } else {
                unlink('files/' . $eventImage->getClientOriginalName());
                throw  new  \Exception('Event Already Exists');
            }

        } else {
            return $this->render('upload/form.html.twig', []);
        }


    }


    public function eventsAction(Request $request): ?Response
    {
        return $this->render('events/events.html.twig', ['events' => $this->eventService->getEvents()]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function resultAction(Request $request, $id): ?Response
    {

        $selectedFilter = $request->get('filter', 'all');

        /** @var Events $event */
        $event = $this->liveEventService->getEventByHash($id);
        $this->liveEventService->incrementEventsViews($event);

        $result = $event->getResultsJson();
        $data = $this->eventFilter->prepareResult($result, $selectedFilter, $event->getRidersListDsqArray());
        $classes = $this->riderResultFinder->getAllClasses($event);

        $params = [
            'filter' => $data['filter'],
            'lapsData' => $data['result'],
            'selectedFilter' => $selectedFilter,
            'event' => $event,
            'id' => $id,
            'classes' => $classes,
            'record' => $this->liveEventService->searchRecord($data['result'])
        ];


        return $this->render('result/results.html.twig', $params);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function lapchartAction(Request $request, $id): ?Response
    {
        date_default_timezone_set('Europe/Kiev');
        $selectedFilter = trim($request->get('filter', 'all'));

        /** @var Events $event */
        $event = $this->liveEventService->getEventByHash($id);
        $this->liveEventService->incrementEventsViews($event);

        $result = $event->getResultsJson();
        $data = $this->eventFilter->prepareResult($result, $selectedFilter, $event->getRidersListDsqArray());
        $result = $this->lapCalculator->lapChartCalculator($data['result']);


        return $this->render('result/protocol.html.twig', [
            'filter' => $data['filter'],
            'lapsData' => $result,
            'selectedFilter' => $selectedFilter,
            'event' => $event,
            'data' => $result,
            'riderCollector' => $this->riderCollector
        ]);
    }


    /**
     * @param Request $request
     * @return Response|null
     */
    public function createLiveEventAction(Request $request): ?Response
    {

        $file1 = $request->files->get('file1');
        $eventName = $request->get('eventName');
        $adminKey = $request->get('key');

        $params = [];
        if ($file1 instanceof UploadedFile && $adminKey === 'sv1826') {
            $file1->move(
                "files",
                $file1->getClientOriginalName()
            );

            $ridersList = file_get_contents('files/' . $file1->getClientOriginalName());

            unlink('files/' . $file1->getClientOriginalName());

            $liveId = $this->liveEventService->createLiveEvent($ridersList, $eventName);

            $params['liveEventId'] = $liveId;
        }

        return $this->render('upload/live_event_create.html.twig', $params);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Response|null
     */
    public function liveEventAction(Request $request, $id): ?Response
    {
        $selectedFilter = $request->get('filter', 'all');
        $params = $this->buildLiveEventData($id,$selectedFilter);

        return $this->render('result/results_live.html.twig', $params);
    }


    /**
     * @param Request $request
     * @return Response|null
     */
    public function liveAjaxAction(Request $request): ?Response
    {
        if ($request->isXmlHttpRequest()) {

            $eventId = $request->get('eventId');
            $csrfValue = $request->get('csrftoken');
            $filter = $request->get('filter');

            $csrfToken = new CsrfToken('live_event', $csrfValue);
            $csrfResult = $this->csrfTokenManager->isTokenValid($csrfToken);

            if (!$csrfResult) {
                throw new InvalidCsrfTokenException('CSRF invalid, request expected');
            }

            $params = $this->buildLiveEventData($eventId,$filter);

            return $this->render('result/live_table.html.twig', $params);
        } else {
            throw new BadRequestHttpException('XHR request expected');
        }
    }


    /**
     * @param $eventId
     * @param $filter
     * @return array
     */
    private function buildLiveEventData($eventId, $filter): array
    {

        $liveEvent =$this->liveEventService->getLiveEventById($eventId);
        $data = $this->eventFilter->prepareResult($liveEvent->getResult(), $filter, []);
        $result = $this->lapCalculator->prepareLiveResult($data['result']);

        return [
            'filter' => $data['filter'],
            'lapsData' => $result,
            'selectedFilter' => $filter,
            'event' => $liveEvent,
            'id' => $eventId,
            'record' => $this->liveEventService->searchRecord($result)
        ];
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function robotsAction(Request $request): Response
    {
        $path = "robots.txt";
        $content = file_get_contents($path);

        return new Response(
            $content,
            200,
            ['Content-Type' => 'text/plain']
        );
    }
}
