<?php

namespace App\Services\Booking;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentProofService
{
    private const DISK = 'local';
    private const TEMP_DIRECTORY = 'payment-proofs/tmp';
    private const FINAL_DIRECTORY = 'payment-proofs/submitted';

    /**
     * Store a temporary proof on a private filesystem disk.
     */
    public function storeTemporary(UploadedFile $file): array
    {
        $path = $file->storeAs(
            self::TEMP_DIRECTORY,
            Str::uuid() . '.' . $file->getClientOriginalExtension(),
            self::DISK
        );

        return $this->metadata($file, $path);
    }

    /**
     * Persist an uploaded or temporary proof for a payment record.
     */
    public function persist(UploadedFile|string $proof): array
    {
        if ($proof instanceof UploadedFile) {
            $path = $proof->storeAs(
                self::FINAL_DIRECTORY,
                Str::uuid() . '.' . $proof->getClientOriginalExtension(),
                self::DISK
            );

            return $this->metadata($proof, $path);
        }

        if (! Str::startsWith($proof, self::TEMP_DIRECTORY . '/') || str_contains($proof, '..')) {
            throw ValidationException::withMessages([
                'payment_proof_temp' => 'The temporary payment proof is invalid.',
            ]);
        }

        if (! Storage::disk(self::DISK)->exists($proof)) {
            throw ValidationException::withMessages([
                'payment_proof_temp' => 'The temporary payment proof has expired or was removed.',
            ]);
        }

        $extension = pathinfo($proof, PATHINFO_EXTENSION);
        $newPath = self::FINAL_DIRECTORY . '/' . Str::uuid() . ($extension ? ".{$extension}" : '');
        Storage::disk(self::DISK)->move($proof, $newPath);

        return [
            'disk' => self::DISK,
            'path' => $newPath,
            'original_name' => basename($proof),
        ];
    }

    private function metadata(UploadedFile $file, string $path): array
    {
        return [
            'disk' => self::DISK,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }
}
