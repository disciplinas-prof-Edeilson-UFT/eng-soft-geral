<?php
declare(strict_types=1);

namespace src\services;

class UploadImageService
{
    private const MAX_SIZE_BYTES = 5_000_000; 

    public static function handleUpload(array $file, string $uploadDir, array $allowedTypes): array
    {
        try {
            $baseDir = __DIR__ . '/../../public/uploads/';
            $uploadFinalDir = $baseDir . trim($uploadDir, '/');

            if (!is_dir($uploadFinalDir) && !mkdir($uploadFinalDir, 0755, true)) {
                return ['success' => false, 'error' => 'Não foi possível criar o diretório de upload'];
            }
            if (!is_writable($uploadFinalDir)) {
                return ['success' => false, 'error' => 'Diretório sem permissão de escrita'];
            }
            if (!isset($file['name'], $file['tmp_name'], $file['error'])) {
                return ['success' => false, 'error' => 'Arquivo inválido'];
            }
            if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                return ['success' => false, 'error' => 'Erro no upload (código ' . $file['error'] . ')'];
            }
            if (!empty($file['size']) && $file['size'] > self::MAX_SIZE_BYTES) {
                return ['success' => false, 'error' => 'Arquivo excede o tamanho máximo de 5MB'];
            }

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedTypes, true)) {
                return ['success' => false, 'error' => 'Apenas ' . implode(', ', $allowedTypes) . ' são permitidos'];
            }

            $baseName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', pathinfo($file['name'], PATHINFO_FILENAME));
            $fileName = uniqid('img_', true) . '_' . $baseName . '.' . $extension;
            $targetFile = $uploadFinalDir . '/' . $fileName;

            if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
                return ['success' => false, 'error' => 'Falha ao mover arquivo'];
            }

            @chmod($targetFile, 0644);
            return [
                'success' => true,
                'file_name' => $fileName,
                'file_path' => $targetFile
            ];
        } catch (\Throwable $e) {
            error_log('UploadImageService exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}