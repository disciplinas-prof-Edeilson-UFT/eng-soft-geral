<?php

namespace src\services;

class UploadImageService{

    public static function handleUpload($file, $uploadDir, $allowedTypes) {
        //mudei para a pasta public pq se nao estiver lá junto ao index.php nao vai ser encontrado 
        $uploadFinalDir = __DIR__ . '/../../public' . $uploadDir;
        
        if (!is_dir($uploadFinalDir)) {
            if (!mkdir($uploadFinalDir, 0777, true)) {
                return ['success' => false, 'error' => "Não foi possível criar o diretório de upload"];
            }
        }

        if (!is_writable($uploadFinalDir)) {
            return ['success' => false, 'error' => "Dir de upload sem permissão de escrita"];
        }

        if (is_array($file) && isset($file['name'])) {
            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        } else {
            return ['success' => false, 'error' => "Arquivo inválido"];
        }
        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'error' => "Apenas arquivos " . implode(", ", $allowedTypes) . " são permitidos"];
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $uploadFinalDir . '/' . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['success' => true, 'file_name' => $fileName];
        } else {
            return ['success' => false, 'error' => "Erro ao fazer upload da imagem para $targetFile"];
        }
    }
}