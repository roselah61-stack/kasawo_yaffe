<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Events, notice board and newsletter subscribers: small lists managed on one screen each. */
class BoardController extends Controller
{
    use Concerns;

    public function events(Request $request): View
    {
        return view('admin.events', [
            'upcoming' => Event::where('starts_at', '>=', now()->startOfDay())->orderBy('starts_at')->get(),
            'past' => Event::where('starts_at', '<', now()->startOfDay())->latest('starts_at')->take(10)->get(),
            'editing' => $request->query('edit') ? Event::find($request->query('edit')) : null,
        ]);
    }

    public function saveEvent(Request $request, ?Event $event = null): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'starts_at' => ['required', 'date'],
            'location' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $event && $event->exists ? $event->update($data) : $event = Event::create($data);

        return redirect()->route('admin.events')->with('status', "<b>{$event->title}</b> saved.");
    }

    public function destroyEvent(Event $event): RedirectResponse
    {
        $event->delete();

        return back()->with('status', "<b>{$event->title}</b> deleted.");
    }

    public function notices(Request $request): View
    {
        return view('admin.notices', [
            'notices' => Notice::latest()->get(),
            'editing' => $request->query('edit') ? Notice::find($request->query('edit')) : null,
        ]);
    }

    public function saveNotice(Request $request, ?Notice $notice = null): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'detail' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:8'],
        ]);
        $data['icon'] = ($data['icon'] ?? null) ?: '📢';
        $data['is_urgent'] = $request->boolean('is_urgent');
        $notice && $notice->exists ? $notice->update($data) : $notice = Notice::create($data);

        return redirect()->route('admin.notices')->with('status', "<b>{$notice->title}</b> posted.");
    }

    public function destroyNotice(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return back()->with('status', 'Notice removed.');
    }

    public function subscribers(): View
    {
        return view('admin.subscribers', ['subscribers' => Subscriber::latest()->paginate(50)]);
    }

    public function exportSubscribers(): StreamedResponse
    {
        return $this->csv('kasawo-subscribers-'.now()->format('Y-m-d').'.csv', ['Contact', 'Joined'],
            Subscriber::latest()->lazy()->map(fn ($s) => [$s->contact, $s->created_at->format('Y-m-d')]));
    }

    public function destroySubscriber(Subscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('status', 'Subscriber removed.');
    }
}
