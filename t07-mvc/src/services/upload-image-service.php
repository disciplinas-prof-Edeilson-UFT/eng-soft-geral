<?php

namespace src\services;

class UploadImageService{

    public static function handleUpload($file, $uploadDir, $allowedTypes) {

        $uploadFinalDir = __DIR__ . '/../uploads' . $uploadDir . '/';

        if (!is_dir($uploadFinalDir)) {
            mkdir($uploadFinalDir, 0777, true);
        }
    
        if (is_array($file) && isset($file['name'])) {
            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        } else {
            return ['success' => false, 'error' => "Arquivo inválido."];
        }
    
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'error' => "Apenas arquivos " . implode(", ", $allowedTypes) . " são permitidos."];
        }
    
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $uploadDir . $fileName;
    
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $fileName = $fileName;
    
            if ($fileName) {
                return ['success' => true, 'file_name' => $fileName];
            } else {
                return ['error' => "Erro ao criar URL da imagem."];
            }
        } else {
            return ['error' => "Erro ao fazer upload da imagem."];
        }
    }
}