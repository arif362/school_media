<?php
declare(strict_types=1);

namespace App\Controller\Student;

class ProfileController extends StudentAppController
{
    public function view()
    {
        $usersTable = $this->fetchTable('Users');
        $userId = $this->request->getAttribute('identity')->id;
        $user = $usersTable->get($userId);

        $this->set(compact('user'));
    }

    public function edit()
    {
        $usersTable = $this->fetchTable('Users');
        $userId = $this->request->getAttribute('identity')->id;
        $user = $usersTable->get($userId);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle avatar upload
            $avatar = $this->request->getData('avatar_file');
            if ($avatar && $avatar->getError() === UPLOAD_ERR_OK) {
                $filename = $this->uploadAvatar($avatar, $userId);
                if ($filename) {
                    $data['avatar'] = $filename;
                }
            }
            unset($data['avatar_file']);

            // Prevent changing sensitive fields
            unset($data['email'], $data['password'], $data['role'], $data['active']);

            $user = $usersTable->patchEntity($user, $data);
            if ($usersTable->save($user)) {
                $this->Flash->success(__('Your profile has been updated.'));

                return $this->redirect(['action' => 'view']);
            }
            $this->Flash->error(__('Unable to update your profile. Please try again.'));
        }

        $gradeLevels = [
            'Grade 1' => 'Grade 1',
            'Grade 2' => 'Grade 2',
            'Grade 3' => 'Grade 3',
            'Grade 4' => 'Grade 4',
            'Grade 5' => 'Grade 5',
            'Grade 6' => 'Grade 6',
            'Grade 7' => 'Grade 7',
            'Grade 8' => 'Grade 8',
            'Grade 9' => 'Grade 9',
            'Grade 10' => 'Grade 10',
            'Grade 11' => 'Grade 11',
            'Grade 12' => 'Grade 12',
        ];

        $this->set(compact('user', 'gradeLevels'));
    }

    private function uploadAvatar($file, int $userId): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mediaType = $file->getClientMediaType();

        if (!in_array($mediaType, $allowedTypes)) {
            $this->Flash->error(__('Invalid file type. Please upload a JPG, PNG, GIF, or WebP image.'));
            return null;
        }

        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file->getSize() > $maxSize) {
            $this->Flash->error(__('File is too large. Maximum size is 2MB.'));
            return null;
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;

        $uploadPath = WWW_ROOT . 'img' . DS . 'avatars' . DS;
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->moveTo($uploadPath . $filename);

        return 'avatars/' . $filename;
    }
}
