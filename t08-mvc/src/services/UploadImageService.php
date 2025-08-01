<?php
namespace src\services;

class UploadImageService {

    public static function handleUpload($file, $uploadDir, $allowedTypes): array {
        try {
            $uploadFinalDir = __DIR__ . '/../../public/uploads/' . trim($uploadDir, '/');
            
            //error_log("UploadImageService: tentando usar o dir: " . $uploadFinalDir);
            
            if (!is_dir($uploadFinalDir)) {
                //error_log("Dir nao existe: " . $uploadFinalDir);
                if (!mkdir($uploadFinalDir, 0755, true)) {
                    //error_log("falha ao criar o dir: " . $uploadFinalDir);
                    return ['success' => false, 'error' => "Não foi possível criar o diretório de upload"];
                }
            }

            if (!is_writable($uploadFinalDir)) {
                //error_log("Dir sem permissao de escrita: " . $uploadFinalDir);
                return ['success' => false, 'error' => "Dir sem permissao de escrita"];
            }

            if (!is_array($file) || !isset($file['name'])) {
                return ['success' => false, 'error' => "Arquivo inválido"];
            }

            $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                return ['success' => false, 'error' => "Apenas arquivos " . implode(", ", $allowedTypes) . " são permitidos"];
            }

            $fileName = uniqid() . '_' . basename($file['name']);
            $targetFile = $uploadFinalDir . '/' . $fileName;
                        
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                chmod($targetFile, 0644);

                return [
                    'success' => true, 
                    'file_name' => $fileName,
                    'file_path' => $targetFile
                ];
            } else {
                return ['success' => false, 'error' => "Erro ao fazer upload da imagem para $targetFile"];
            }

        } catch (\Exception $e) {
            error_log("UploadImageService exception: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}