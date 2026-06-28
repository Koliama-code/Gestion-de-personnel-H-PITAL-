<?php
require_once __DIR__ . '/../Models/Employe.php';
require_once __DIR__ . '/AuthController.php';

class EmployeController
{

    private function uploadPhoto($file, $matricule)
    {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/employes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($extension, $allowedExtensions)) {
                return null;
            }

            $newFileName = $matricule . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $newFileName;
            }
        }
        return null;
    }

    public function listAction()
    {
        $user = AuthController::checkRole('rh');
        $employes = Employe::getAll();
        include __DIR__ . '/../Views/employes/liste.php';
    }

    public function addAction()
    {
        $user = AuthController::checkRole('rh');
        $photoName = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $matricule = htmlspecialchars(trim($_POST['matricule']));
                $photoName = $this->uploadPhoto($_FILES['photo'], $matricule);
            }

            $data = [
                'matricule' => htmlspecialchars(trim($_POST['matricule'])),
                'nom' => htmlspecialchars(trim($_POST['nom'])),
                'prenom' => htmlspecialchars(trim($_POST['prenom'])),
                'date_naissance' => $_POST['date_naissance'],
                'sexe' => $_POST['sexe'],
                'email' => htmlspecialchars(trim($_POST['email'])),
                'telephone' => htmlspecialchars(trim($_POST['telephone'])),
                'adresse' => htmlspecialchars(trim($_POST['adresse'])),
                'numero_ordre' => htmlspecialchars(trim($_POST['numero_ordre'])),
                'date_embauche' => $_POST['date_embauche'],
                'poste_occupe' => htmlspecialchars(trim($_POST['poste_occupe'])),
                'id_service' => $_POST['id_service'] ?: null,
                'id_categorie' => $_POST['id_categorie'] ?: null,
                'id_specialite' => $_POST['id_specialite'] ?: null,
                'statut_employe' => $_POST['statut_employe'],
                'salaire_base' => $_POST['salaire_base'],
                'photo' => $photoName
            ];

            if (Employe::create($data)) {
                header('Location: index.php?action=employes&success=1');
                exit;
            } else {
                $error = "Erreur lors de l'enregistrement.";
            }
        }

        $services = Employe::getServices();
        $categories = Employe::getCategories();
        $specialites = Employe::getSpecialites();
        include __DIR__ . '/../Views/employes/ajouter.php';
    }

    public function editAction()
    {
        $user = AuthController::checkRole('rh');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=employes');
            exit;
        }

        $employe = Employe::getById($id);
        if (!$employe) {
            header('Location: index.php?action=employes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photoName = $employe['photo'];
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $matricule = htmlspecialchars(trim($_POST['matricule']));
                $newPhoto = $this->uploadPhoto($_FILES['photo'], $matricule);
                if ($newPhoto) {
                    $photoName = $newPhoto;
                    if ($employe['photo'] && file_exists(__DIR__ . '/../../public/uploads/employes/' . $employe['photo'])) {
                        unlink(__DIR__ . '/../../public/uploads/employes/' . $employe['photo']);
                    }
                }
            }

            $data = [
                'matricule' => htmlspecialchars(trim($_POST['matricule'])),
                'nom' => htmlspecialchars(trim($_POST['nom'])),
                'prenom' => htmlspecialchars(trim($_POST['prenom'])),
                'date_naissance' => $_POST['date_naissance'],
                'sexe' => $_POST['sexe'],
                'email' => htmlspecialchars(trim($_POST['email'])),
                'telephone' => htmlspecialchars(trim($_POST['telephone'])),
                'adresse' => htmlspecialchars(trim($_POST['adresse'])),
                'numero_ordre' => htmlspecialchars(trim($_POST['numero_ordre'])),
                'date_embauche' => $_POST['date_embauche'],
                'poste_occupe' => htmlspecialchars(trim($_POST['poste_occupe'])),
                'id_service' => $_POST['id_service'] ?: null,
                'id_categorie' => $_POST['id_categorie'] ?: null,
                'id_specialite' => $_POST['id_specialite'] ?: null,
                'statut_employe' => $_POST['statut_employe'],
                'salaire_base' => $_POST['salaire_base'],
                'photo' => $photoName
            ];

            if (Employe::update($id, $data)) {
                header('Location: index.php?action=employes&success=1');
                exit;
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        }

        $services = Employe::getServices();
        $categories = Employe::getCategories();
        $specialites = Employe::getSpecialites();
        include __DIR__ . '/../Views/employes/modifier.php';
    }

    public function deleteAction()
    {
        $user = AuthController::checkRole('rh');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $employe = Employe::getById($id);
            if ($employe && $employe['photo'] && file_exists(__DIR__ . '/../../public/uploads/employes/' . $employe['photo'])) {
                unlink(__DIR__ . '/../../public/uploads/employes/' . $employe['photo']);
            }
            Employe::delete($id);
        }
        header('Location: index.php?action=employes&success=1');
        exit;
    }
}
