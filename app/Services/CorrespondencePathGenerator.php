<?php

namespace App\Services;

use App\Models\Correspondence;
use App\Models\CorrespondenceMovement;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CorrespondencePathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $model = $media->model;

        // Handle CorrespondenceMovement attachments
        if ($model instanceof CorrespondenceMovement) {
            $correspondence = $model->correspondence;

            // Put movement attachments in the same folder as the main correspondence
            // to keep the structure simple as requested by the user
            return $this->generateCorrespondencePath($correspondence);
        }

        // Handle Correspondence attachments
        if ($model instanceof Correspondence) {
            return $this->generateCorrespondencePath($model);
        }

        // Fallback to default
        return $this->getBasePath($media).'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'responsive/';
    }

    protected function generateCorrespondencePath(Correspondence $correspondence): string
    {
        // Determine register type folder
        $registerType = $correspondence->type === 'Receipt' ? 'Receipt Register' : 'Dispatch Register';

        // Get the date (received_date for Receipt, dispatch_date for Dispatch)
        $date = $correspondence->type === 'Receipt'
            ? $correspondence->received_date
            : $correspondence->dispatch_date;

        // Fallback to created_at if no date is set
        if (! $date) {
            $date = $correspondence->created_at;
        }

        // Format: Register Type/YYYY-MM-DD/REGISTER-NUMBER
        // Simplified as requested: removed month folder
        $dateFolder = $date->format('Y-m-d');
        $registerNumber = $correspondence->register_number;

        return "{$registerType}/{$dateFolder}/{$registerNumber}/";
    }

    protected function getBasePath(Media $media): string
    {
        return $media->getKey();
    }
}
