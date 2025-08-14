<?php

namespace App\Filament\Resources\FacilityResource\Pages;

use App\Filament\Resources\FacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateFacility extends CreateRecord
{
    protected static string $resource = FacilityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure image field is properly handled
        if (isset($data['image']) && is_array($data['image'])) {
            $data['image'] = $data['image'][0] ?? null;
        }

        // Set default values
        $data['is_active'] = $data['is_active'] ?? true;
        $data['order_index'] = $data['order_index'] ?? 0;
        $data['specifications'] = $data['specifications'] ?? [];

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error creating facility')
                ->body('Please check your input and try again.')
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Facility created successfully')
            ->success()
            ->send();
    }
}