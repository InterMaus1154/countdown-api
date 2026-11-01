<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimerRequest;
use App\Models\Timer;
use App\Services\AuthService;
use App\Services\TimerService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;

class TimerController extends Controller
{
    public function __construct(private AuthService $auth, private LoggerInterface $logger, private TimerService $timers)
    {
    }

    public function index(): View
    {
        return view('timers.index');
    }

    public function create(): View
    {
        return view('timers.create');
    }

    public function store(StoreTimerRequest $request): RedirectResponse
    {
        $constructedDateTime = sprintf('%s %s', $request->input('end_date'), $request->input('end_time'));

        try{
            $timer = Timer::make(
                title: $request->input('title'),
                endsAt: Carbon::parse($constructedDateTime),
                userId: $this->auth->getUserId(),
                description: $request->input('description', null)
            );
            $this->timers->commit($timer);
            $this->logTimerActivity('TIMER_CREATED', 'Timer created with id ' . $timer->getId(), ['timer' => $timer]);
            return to_route('timers.index');
        }catch (\Throwable $e) {
            $this->logTimerActivity('ERROR_CREATING_TIMER', $e->getMessage(), ['data' => $request->validated()]);
            return redirect()->back()->withErrors(['error_generic' => 'Error creating timer']);
        }
    }


    private function logTimerActivity(string $activity, string $message, array $context = []): void
    {
        $finalMessage = sprintf('[Timer Activity] Caused by %s: Activity type: %s, %s', $this->auth->getUserId(), $activity, $message);
        $this->logger->info($finalMessage, $context);
    }
}
