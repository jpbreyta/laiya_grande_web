<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptsData
{
    /**
     * Get the list of attributes to encrypt
     * Override this in your model
     */
    protected function getEncryptedAttributes(): array
    {
        return $this->encrypted ?? [];
    }

    /**
     * Encrypt attributes before saving
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->getEncryptedAttributes()) && !is_null($value)) {
            $value = Crypt::encryptString($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Decrypt attributes when retrieving
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->getEncryptedAttributes()) && !is_null($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (\Exception $e) {
                // If decryption fails, return original value
                // (might be unencrypted legacy data)
            }
        }

        return $value;
    }

    /**
     * Get array of attributes without decrypting
     */
    public function getAttributesWithoutDecryption(): array
    {
        return $this->attributes;
    }
}
