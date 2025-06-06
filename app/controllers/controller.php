<?php


    function redirect_to_root(string $path): void {
        header('Location: index.php?page=' . $path);
        exit;
    }


    function render_view(string $view, array $data = [], string $layout = null): void {
        extract($data);
    
        $viewPath = __DIR__ . '/../views/' . $view . '.view.php';
    
        if ($layout) {
            ob_start();
            
            require $viewPath;
            $content = ob_get_clean();
    
            require __DIR__ . '/../views/layout/' . $layout . '.layout.php';
        } else {
            require $viewPath;
        }
    }
    


    function save_photo(array $file, string $uploadDir = 'uploads/promotions/'): string|bool {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        var_dump($file); // ← Ajoute ceci pour inspecter le contenu
        if (!file_exists($file['tmp_name'])) {
            echo "Fichier temporaire introuvable.";
            return false;
        }
        var_dump(is_writable($uploadDir)); // Doit retourner true

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('promo_', true) . '.' . $extension;
        $destination = $uploadDir . $filename;
    
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        } else {
            echo "Échec du move_uploaded_file vers $destination";
        }
    
        return false;
    }
    
    