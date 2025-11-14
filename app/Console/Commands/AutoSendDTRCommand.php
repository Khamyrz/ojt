<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Intern;
use App\Models\TimeLog;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AutoSendDTRCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dtr:auto-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate and save DTR as document for all interns every Friday at 5:01 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting DTR auto-send process...');
        
        $now = Carbon::now('Asia/Manila');
        $currentWeekStart = $now->copy()->startOfWeek();
        $currentWeekEnd = $now->copy()->endOfWeek();
        
        // Get all accepted interns
        $interns = Intern::where('status', 'accepted')->get();
        
        $count = 0;
        
        foreach ($interns as $intern) {
            // Get time logs for the current week
            $logs = TimeLog::where('intern_id', $intern->id)
                ->whereBetween('date', [$currentWeekStart->toDateString(), $currentWeekEnd->toDateString()])
                ->orderBy('date', 'asc')
                ->get();
            
            if ($logs->isEmpty()) {
                continue;
            }
            
            // Check if DTR already exists for this week
            $weekDescription = 'DTR - Week ending ' . $currentWeekEnd->format('F j, Y');
            $existingDTR = Document::where('intern_id', $intern->id)
                ->where('type', 'dtr')
                ->where('description', $weekDescription)
                ->first();
            
            if ($existingDTR) {
                $this->info("DTR already exists for {$intern->first_name} {$intern->last_name} for this week.");
                continue;
            }
            
            // Generate DTR HTML
            $dtrHtml = $this->generateDTRHtml($intern, $logs, $currentWeekEnd);
            
            // Save DTR as HTML file
            $filename = 'dtr_' . $intern->id . '_' . $currentWeekEnd->format('Y-m-d') . '.html';
            $path = 'dtrs/' . $filename;
            Storage::disk('public')->put($path, $dtrHtml);
            
            // Save as document
            Document::create([
                'intern_id' => $intern->id,
                'type' => 'dtr',
                'path' => 'storage/' . $path,
                'filename' => $filename,
                'submitted_at' => $now,
                'description' => $weekDescription,
                'forwarded_to_admin' => true,
            ]);
            
            $count++;
            $this->info("DTR generated for {$intern->first_name} {$intern->last_name}");
        }
        
        $this->info("DTR auto-send completed. {$count} DTR(s) generated.");
        
        return 0;
    }
    
    /**
     * Generate DTR HTML content
     */
    private function generateDTRHtml($intern, $logs, $weekEnd)
    {
        // The view already handles all calculations, just pass the data
        try {
            $html = view('dtr', compact('intern', 'logs'))->render();
            return $html;
        } catch (\Exception $e) {
            $this->error("Error generating DTR HTML for intern {$intern->id}: " . $e->getMessage());
            return '<html><body><h1>Error generating DTR</h1></body></html>';
        }
    }
}

