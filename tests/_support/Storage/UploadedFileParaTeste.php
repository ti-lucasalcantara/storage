<?php

namespace Tests\Support\Storage;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * UploadedFile para uso em testes: isValid() e move() são ajustados para arquivos
 * que não vieram de upload HTTP (is_uploaded_file() seria false em testes).
 */
class UploadedFileParaTeste extends UploadedFile
{
    public function isValid(): bool
    {
        return $this->getError() === UPLOAD_ERR_OK && is_file($this->path);
    }

    public function move(string $targetPath, ?string $name = null, bool $overwrite = false): bool
    {
        $targetPath = rtrim($targetPath, '/') . '/';
        $name       = $name ?? $this->getName();
        $destination = $targetPath . $name;

        if (! is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        if ($this->hasMoved) {
            throw new \RuntimeException('Arquivo já foi movido.');
        }

        if (! $this->isValid()) {
            throw new \RuntimeException('Arquivo inválido.');
        }

        if (! copy($this->path, $destination)) {
            throw new \RuntimeException('Falha ao copiar o arquivo para o destino.');
        }

        @unlink($this->path);
        $this->hasMoved = true;
        $this->path     = $destination;
        $this->name     = basename($destination);

        return true;
    }
}
