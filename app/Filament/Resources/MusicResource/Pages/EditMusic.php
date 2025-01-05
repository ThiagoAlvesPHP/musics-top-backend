<?php

namespace App\Filament\Resources\MusicResource\Pages;

use App\Filament\Resources\MusicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\MusicTrait;
use Exception;
use Filament\Notifications\Notification;

class EditMusic extends EditRecord
{
    protected static string $resource = MusicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Filling URL
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['url'] = 'https://www.youtube.com/watch?v=' . $data['youtube_id'];

        return $data;
    }

    /**
     * Customizing data editing before sending it
     */
    protected function mutateFormDataBeforeSave(array $data): array
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
            if ($videoId !== $this->record->youtube_id) {
                // Search video information
                $data = array_merge($data, MusicTrait::getVideoInfo($videoId));
            }
        
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

    /**
     * Customizing actions after saving data
     */
    protected function afterSave()
    {
        return redirect(EditMusic::getUrl(['record' => $this->record]));
    }
}
