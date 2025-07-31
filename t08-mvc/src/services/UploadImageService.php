<?php
namespace src\services;

class UploadImageService {

    public static function handleUpload($file, $uploadDir, $allowedTypes): array {
        try {
            $uploadFinalDir = __DIR__ . '/../../public/uploads/' . trim($uploadDir, '/');
            
            error_log("UploadImageService: Trying to use directory: " . $uploadFinalDir);
            
            // ✅ Verificar se diretório existe
            if (!is_dir($uploadFinalDir)) {
                error_log("Directory does not exist: " . $uploadFinalDir);
                // ✅ Como já foi criado no Dockerfile, não deveria chegar aqui
                if (!mkdir($uploadFinalDir, 0755, true)) {
                    error_log("Failed to create directory: " . $uploadFinalDir);
                    return ['success' => false, 'error' => "Não foi possível criar o diretório de upload"];
                }
            }

            // ✅ Verificar permissões
            if (!is_writable($uploadFinalDir)) {
                error_log("Directory is not writable: " . $uploadFinalDir);
                return ['success' => false, 'error' => "Diretório sem permissão de escrita"];
            }

            // ✅ Validar arquivo
            if (!is_array($file) || !isset($file['name'])) {
                return ['success' => false, 'error' => "Arquivo inválido"];
            }

            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                return ['success' => false, 'error' => "Apenas arquivos " . implode(", ", $allowedTypes) . " são permitidos"];
            }

            // ✅ Gerar nome seguro
            $fileName = uniqid() . '_' . basename($file['name']);
            $targetFile = $uploadFinalDir . '/' . $fileName;
            
            error_log("Attempting to move file to: " . $targetFile);
            
            // ✅ Upload
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                chmod($targetFile, 0644);
                error_log("File uploaded successfully: " . $targetFile);
                
                return [
                    'success' => true, 
                    'file_name' => $fileName,
                    'file_path' => $targetFile
                ];
            } else {
                error_log("Failed to move uploaded file");
                return ['success' => false, 'error' => "Erro ao fazer upload da imagem para $targetFile"];
            }

        } catch (\Exception $e) {
            error_log("UploadImageService exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}