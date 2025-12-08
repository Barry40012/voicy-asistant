<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $newsletter;
    public $subscriber;

    /**
     * Create a new job instance.
     */
    public function __construct(Newsletter $newsletter, NewsletterSubscriber $subscriber)
    {
        $this->newsletter = $newsletter;
        $this->subscriber = $subscriber;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Vérifier que l'abonné est toujours actif
            if (!$this->subscriber->is_active) {
                Log::info("Newsletter: Subscriber {$this->subscriber->email} is not active, skipping");
                $this->newsletter->increment('failed_count');
                return;
            }

            // Envoyer l'email
            Mail::to($this->subscriber->email)->send(
                new NewsletterMail(
                    $this->newsletter->subject,
                    $this->newsletter->content,
                    $this->subscriber->email
                )
            );

            // Mettre à jour le compteur
            $this->newsletter->increment('sent_count');
            
            // Vérifier si tous les emails ont été envoyés et mettre à jour le statut
            $this->newsletter->refresh();
            $totalProcessed = $this->newsletter->sent_count + $this->newsletter->failed_count;
            
            if ($totalProcessed >= $this->newsletter->total_recipients && $this->newsletter->status === 'sending') {
                $this->newsletter->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                Log::info("Newsletter #{$this->newsletter->id} completed: {$this->newsletter->sent_count} sent, {$this->newsletter->failed_count} failed");
            }
            
            Log::info("Newsletter sent successfully to {$this->subscriber->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send newsletter to {$this->subscriber->email}: " . $e->getMessage());
            $this->newsletter->increment('failed_count');
            
            // Relancer le job en cas d'erreur temporaire (max 3 tentatives)
            if ($this->attempts() < 3) {
                throw $e;
            }
        }
    }
}
