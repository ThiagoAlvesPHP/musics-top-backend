<?php

namespace App\Filament\Resources\MusicResource\Pages;

use App\Filament\Resources\MusicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\MusicTrait;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateMusic extends CreateRecord
{
    protected static string $resource = MusicResource::class;

    /**
     * Customizing the data before sending it to create the music
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract the video ID
        $videoId = MusicTrait::extractVideoId($data['url']);

        if (!$videoId) {
            Notification::make()
                ->title('Não foi possível capturar o ID do vídeo')
                ->danger()
                ->send();

            $this->halt();
        }

        try {
            // Search video information
            $data = MusicTrait::getVideoInfo($videoId);
            // Get the ID of the user who suggested the video
            $data['user_id'] = Auth::user()->id;
        
            // Send data
            return $data;
        } catch (Exception $error) {
            Notification::make()
                ->title('Erro ao tentar capturar dados do vídeo')
                ->body($error->getMessage())
                ->danger()
                ->send();
            
            $this->halt();
        }
    }
}
