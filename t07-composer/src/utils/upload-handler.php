<?php
class UploadHandler
{

    public static function handleUpload($file, $uploadDir, $allowedTypes, $dbUpdateCallback)
    {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes)) {
            return ['success' => false, 'error' => "Apenas arquivos " . implode(", ", $allowedTypes) . " são permitidos."];
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $photoUrl = $fileName;

            $dbUpdateResult = $dbUpdateCallback($photoUrl);

            if ($dbUpdateResult) {
                return ['success' => true, 'photoUrl' => $photoUrl];
            } else {
                return ['success' => false, 'error' => "Erro ao atualizar o banco de dados."];
            }
        } else {
            return ['success' => false, 'error' => "Erro ao fazer upload da imagem."];
        }
    }
}
