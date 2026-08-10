<?php
// app/Jobs/ExportSubscribersCsvJob.php
namespace App\Jobs;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportSubscribersCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filename;

    public function __construct(?string $filename = null)
    {
        $this->filename = $filename ?? 'subscribers_' . now()->format('Y-m-d_His') . '.csv';
    }

    public function handle(): void
    {
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Email', 'Nom', 'Source', 'Confirme le', 'Desinscrit le', 'Inscrit le']);

        Subscriber::orderBy('created_at')->chunk(500, function ($subscribers) use ($handle) {
            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->email,
                    $subscriber->name,
                    $subscriber->source,
                    optional($subscriber->confirmed_at)->toDateTimeString(),
                    optional($subscriber->unsubscribed_at)->toDateTimeString(),
                    $subscriber->created_at->toDateTimeString(),
                ]);
            }
        });

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        Storage::disk('local')->put('exports/' . $this->filename, $csv);
    }
}
