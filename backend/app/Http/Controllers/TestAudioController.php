<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAudioJob;
use App\Models\Audio;
use App\Services\AudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TestAudioController extends Controller
{
    public function __construct(
        private AudioService $audioService
    ) {}

    /**
     * Show test audio upload page
     */
    public function index()
    {
        return view('dashboard.test-audio.index');
    }

    /**
     * Handle test audio upload
     */
    public function upload(Request $request)
    {
        // Custom validation: accept any file that starts with "audio/"
        $request->validate([
            'audio' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    if (!$value || !$value->isValid()) {
                        $fail('Le fichier est invalide.');
                        return;
                    }
                    
                    $mimeType = $value->getMimeType();
                    $extension = strtolower($value->getClientOriginalExtension());
                    
                    // Accept if MIME type starts with "audio/" OR extension is in our list
                    $validExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'mp4', 'webm', 'aac', 'flac', 'mpeg', 'mpga', 'wma'];
                    
                    if (!str_starts_with($mimeType, 'audio/') && !in_array($extension, $validExtensions)) {
                        $fail('Le fichier doit être un fichier audio (type MIME: ' . $mimeType . ', extension: ' . $extension . ').');
                    }
                },
                'max:10240', // 10MB max
            ],
            'sender_phone' => 'nullable|string|max:20',
        ], [
            'audio.required' => 'Veuillez sélectionner un fichier audio.',
            'audio.file' => 'Le fichier doit être un fichier valide.',
            'audio.max' => 'Le fichier ne doit pas dépasser 10 MB.',
        ]);

        try {
            // Get uploaded file
            $file = $request->file('audio');
            
            // Validate file exists and is readable
            if (!$file || !$file->isValid()) {
                return back()->withErrors(['error' => 'Fichier invalide ou corrompu.']);
            }
            
            $audioContent = file_get_contents($file->getRealPath());
            
            if (!$audioContent) {
                return back()->withErrors(['error' => 'Impossible de lire le contenu du fichier.']);
            }
            
            // Get file extension (fallback to original extension if MIME type detection fails)
            $extension = $file->getClientOriginalExtension();
            if (empty($extension)) {
                // Try to detect from MIME type
                $mimeType = $file->getMimeType();
                $extension = match($mimeType) {
                    'audio/mpeg', 'audio/mp3' => 'mp3',
                    'audio/wav', 'audio/x-wav' => 'wav',
                    'audio/ogg' => 'ogg',
                    'audio/x-m4a', 'audio/mp4' => 'm4a',
                    'audio/webm' => 'webm',
                    'audio/aac' => 'aac',
                    'audio/flac' => 'flac',
                    default => 'ogg', // Default to ogg
                };
            }
            
            // Upload to Supabase Storage
            $filename = 'test_' . uniqid() . '_' . time() . '.' . $extension;
            
            \Log::info('Uploading audio to Supabase', [
                'filename' => $filename,
                'size' => strlen($audioContent),
            ]);
            
            $filePath = $this->audioService->uploadAudio($audioContent, $filename);

            if (!$filePath) {
                \Log::error('Failed to upload audio to Supabase');
                return back()->withErrors(['error' => 'Erreur lors de l\'upload de l\'audio vers Supabase. Vérifiez votre configuration Supabase.']);
            }

            \Log::info('Audio uploaded to Supabase', ['file_path' => $filePath]);

            // Create audio record
            try {
                $audio = Audio::create([
                    'user_id' => Auth::id(),
                    'whatsapp_message_id' => 'test_' . uniqid(),
                    'sender_phone' => $request->input('sender_phone', '+221000000000'),
                    'file_path' => $filePath,
                    'size_bytes' => $file->getSize(),
                    'status' => 'uploaded',
                ]);

                \Log::info('Audio record created', ['audio_id' => $audio->id]);

                // Dispatch job to process audio
                // If queue is 'sync', it will process immediately
                ProcessAudioJob::dispatch($audio);

                \Log::info('ProcessAudioJob dispatched', ['audio_id' => $audio->id]);

                return redirect()->route('dashboard.audios.show', $audio)
                    ->with('success', 'Audio uploadé avec succès ! Le traitement est en cours...');
            } catch (\Exception $e) {
                \Log::error('Error creating audio record', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return back()->withErrors(['error' => 'Erreur lors de la création de l\'enregistrement audio : ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }
}

