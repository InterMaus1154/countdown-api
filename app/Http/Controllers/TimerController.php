<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimerRequest;
use App\Models\Timer;
use App\Services\AuthService;
use App\Services\TimerService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Psr\Log\LoggerInterface;

class TimerController extends Controller
{
    public function __construct(private AuthService $auth, private LoggerInterface $logger, private TimerService $timers)
    {
    }

    public function index(): View
    {
        $timers = $this->timers->all();
        return view('timers.index', compact('timers'));
    }

    public function create(): View
    {
        return view('timers.create');
    }

    public function store(StoreTimerRequest $request): RedirectResponse
    {
        $constructedDateTime = sprintf('%s %s', $request->input('end_date'), $request->input('end_time', '00:00'));

        try {
            $timer = Timer::make(
                title: $request->input('title'),
                endsAt: Carbon::parse($constructedDateTime),
                userId: $this->auth->getUserId(),
                description: $request->input('description', null)
            );
            $this->timers->commit($timer);
            $this->logTimerActivity('TIMER_CREATED', 'Timer created with id ' . $timer->getId(), ['timer' => $timer]);
            return to_route('timers.index');
        } catch (\Throwable $e) {
            $this->logTimerActivity('ERROR_CREATING_TIMER', $e->getMessage(), ['data' => $request->validated()]);
            return redirect()->back()->withErrors(['error_generic' => 'Error creating timer']);
        }
    }


    private function logTimerActivity(string $activity, string $message, array $context = []): void
    {
        $finalMessage = sprintf('[Timer Activity] Caused by %s: Activity type: %s, %s', $this->auth->getUserId(), $activity, $message);
        $this->logger->info($finalMessage, $context);
    }

    public function delete(int $timerId): RedirectResponse
    {
        $timer = $this->timers->findById($timerId);
        if (is_null($timer)) {
            return redirect()->back()->withErrors(['error' => 'Timer not found']);
        }

        $timer->markDeleted();
        $this->timers->commit($timer);
        return redirect()->back()->with('success', 'Timer deleted');
    }

    public function edit(int $timerId): View|RedirectResponse
    {
        $timer = $this->timers->findById($timerId);
        if (is_null($timer)) {
            return to_route('timers.index')->withErrors(['error' => 'Timer not found']);
        }

        return view('timers.edit', compact('timer'));
    }

    public function update(StoreTimerRequest $request, int $timerId): RedirectResponse
    {
        $timer = $this->timers->findById($timerId);
        if (is_null($timer)) {
            return to_route('timers.index')->withErrors(['error' => 'Timer not found']);
        }

        try{
            $timer->setTitle($request->input(('title')));
            $timer->setDescription($request->input('description', null));

            $constructedDateTime = sprintf('%s %s', $request->input('end_date'), $request->input('end_time', '00:00'));
            $timer->setEndsAt(Carbon::parse($constructedDateTime));

            $this->timers->commit($timer);

            return to_route('timers.index')->with('success', 'Timer updated');
        }catch (\Throwable $e) {
            $this->logTimerActivity('ERROR_UPDATING_TIMER', $e->getMessage(), ['timer' => $timer, 'request' => $request->validated()]);
            return to_route('timers.index')->withErrors(['error' => 'Error updating timer']);
        }
    }
}
