<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Images\Exceptions\ImageException;

class Users extends BaseController
{
    /** Where prepared avatars are stored (inside the public web root). */
    private const AVATAR_DIR = FCPATH . 'uploads/avatars/';

    /** Display-ready copy (edit page preview) and thumbnail (listing page) sizes, in px. */
    private const DISPLAY_SIZE = 400;
    private const THUMB_SIZE   = 96;

    protected UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    /** GET /users — User Accounts listing with avatar thumbnails. */
    public function index()
    {
        return view('users/index', [
            'title'  => 'User Accounts',
            'active' => 'users',
            'users'  => $this->users->orderBy('id', 'DESC')->findAll(),
            'roles'  => UserModel::ROLES,
        ]);
    }

    /** GET /users/new — blank "New User" form. */
    public function new()
    {
        return view('users/form', [
            'title'  => 'New User',
            'active' => 'users',
            'user'   => null,
            'roles'  => UserModel::ROLES,
            'action' => site_url('users'),
        ]);
    }

    /** POST /users — validate, then insert. */
    public function create()
    {
        if (! $this->validate($this->rules(), $this->messages())) {
            return redirect()->back()->withInput();
        }

        $data                  = $this->payload();
        $data['password_hash'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);

        $id = $this->users->insert($data);

        return redirect()->to('users')
            ->with('success', 'User “' . $data['username'] . '” was created.')
            ->with('highlight', $id);
    }

    /** GET /users/{id}/edit — pre-filled form with the avatar uploader. */
    public function edit(int $id)
    {
        return view('users/form', [
            'title'  => 'Edit User',
            'active' => 'users',
            'user'   => $this->findOr404($id),
            'roles'  => UserModel::ROLES,
            'action' => site_url('users/' . $id),
        ]);
    }

    /** POST /users/{id} — validate fields + optional avatar, then update. */
    public function update(int $id)
    {
        $user = $this->findOr404($id);

        $avatar  = $this->request->getFile('avatar');
        $hasFile = $avatar instanceof UploadedFile && $avatar->getError() !== UPLOAD_ERR_NO_FILE;

        if (! $this->validate($this->rules($id, $hasFile), $this->messages())) {
            return redirect()->back()->withInput();
        }

        $data = $this->payload();

        // Only change the password when a new one was typed.
        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($hasFile) {
            try {
                $data['avatar'] = $this->storeAvatar($avatar);
            } catch (ImageException $e) {
                log_message('error', 'Avatar processing failed: {msg}', ['msg' => $e->getMessage()]);

                return redirect()->back()->withInput()
                    ->with('error', 'The picture could not be processed. Please try a different JPG or PNG file.');
            }

            // The new file is in place — remove the old copies so they don't pile up.
            $this->deleteAvatar($user['avatar']);
        }

        $this->users->update($id, $data);

        return redirect()->to('users')
            ->with('success', 'User “' . $data['username'] . '” was updated.')
            ->with('highlight', $id);
    }

    // ------------------------------------------------------------------
    // Validation
    // ------------------------------------------------------------------

    private function rules(?int $id = null, bool $hasFile = false): array
    {
        $isEdit = $id !== null;

        $rules = [
            'username'  => 'required|min_length[3]|max_length[50]|regex_match[/^[A-Za-z0-9._-]+$/]|'
                . ($isEdit ? "is_unique[users.username,id,{$id}]" : 'is_unique[users.username]'),
            'full_name' => 'required|min_length[3]|max_length[120]',
            'email'     => 'permit_empty|valid_email|max_length[150]',
            'role'      => 'required|in_list[' . implode(',', array_keys(UserModel::ROLES)) . ']',
            // Password is required on create; optional on edit (blank = keep current).
            'password'         => ($isEdit ? 'permit_empty' : 'required') . '|min_length[8]|max_length[72]',
            'password_confirm' => ($isEdit ? 'permit_empty|required_with[password]' : 'required') . '|matches[password]',
        ];

        // Avatar rules run only when a file was actually chosen, so the
        // edit form can be saved without re-uploading a picture.
        if ($hasFile) {
            $rules['avatar'] = 'uploaded[avatar]'
                . '|is_image[avatar]'
                . '|mime_in[avatar,image/jpg,image/jpeg,image/pjpeg,image/png,image/x-png]'
                . '|ext_in[avatar,jpg,jpeg,png]'
                . '|max_size[avatar,2048]'
                . '|max_dims[avatar,6000,6000]';
        }

        return $rules;
    }

    private function messages(): array
    {
        return [
            'username' => [
                'required'   => 'Username is required.',
                'regex_match' => 'Username may only contain letters, numbers, dots (.), underscores (_) and dashes (-).',
                'min_length' => 'Username must be at least 3 characters.',
                'max_length' => 'Username cannot exceed 50 characters.',
                'is_unique'  => 'That username is already taken. Please choose another.',
            ],
            'full_name' => [
                'required'   => 'Full name is required.',
                'min_length' => 'Full name must be at least 3 characters.',
                'max_length' => 'Full name cannot exceed 120 characters.',
            ],
            'email' => [
                'valid_email' => 'Please enter a valid email address (e.g. name@example.com).',
                'max_length'  => 'Email cannot exceed 150 characters.',
            ],
            'role' => [
                'required' => 'Please choose a role.',
                'in_list'  => 'Please choose a valid role.',
            ],
            'password' => [
                'required'   => 'Password is required for new accounts.',
                'min_length' => 'Password must be at least 8 characters.',
                'max_length' => 'Password cannot exceed 72 characters.',
            ],
            'password_confirm' => [
                'required'      => 'Please confirm the password.',
                'required_with' => 'Please confirm the new password.',
                'matches'       => 'The two passwords do not match.',
            ],
            'avatar' => [
                'uploaded' => 'The picture failed to upload. It may be larger than 2MB.',
                'is_image' => 'The selected file is not a valid image.',
                'mime_in'  => 'Only JPG and PNG images are allowed.',
                'ext_in'   => 'Only .jpg, .jpeg and .png files are allowed.',
                'max_size' => 'The picture must be 2MB or smaller.',
                'max_dims' => 'The picture is too large (max 6000 × 6000 pixels).',
            ],
        ];
    }

    /** Only whitelisted, trimmed fields go to the database. */
    private function payload(): array
    {
        $post = $this->request->getPost(['username', 'full_name', 'email', 'role']);

        return [
            'username'  => trim((string) $post['username']),
            'full_name' => trim((string) $post['full_name']),
            'email'     => strtolower(trim((string) $post['email'])) ?: null,
            'role'      => (string) $post['role'],
        ];
    }

    // ------------------------------------------------------------------
    // Avatar handling
    // ------------------------------------------------------------------

    /**
     * Prepares and stores the uploaded avatar, returning ONLY the filename.
     *
     *  1. A random, server-chosen filename (never the client's filename).
     *  2. A square, display-ready copy  -> uploads/avatars/{name}
     *  3. A small square thumbnail      -> uploads/avatars/thumbs/{name}
     *
     * Both are re-encoded through the Image service (GD), which also strips
     * anything that isn't pixel data from the original file. The raw temp
     * upload is discarded by PHP at the end of the request.
     */
    private function storeAvatar(UploadedFile $file): string
    {
        $ext      = $file->guessExtension() === 'png' ? 'png' : 'jpg';
        $filename = bin2hex(random_bytes(12)) . '.' . $ext;

        $this->ensureDir(self::AVATAR_DIR);
        $this->ensureDir(self::AVATAR_DIR . 'thumbs/');

        $image = service('image');

        $image->withFile($file->getTempName())
            ->fit(self::DISPLAY_SIZE, self::DISPLAY_SIZE, 'center')
            ->save(self::AVATAR_DIR . $filename, 85);

        $image->withFile($file->getTempName())
            ->fit(self::THUMB_SIZE, self::THUMB_SIZE, 'center')
            ->save(self::AVATAR_DIR . 'thumbs/' . $filename, 85);

        return $filename;
    }

    private function deleteAvatar(?string $filename): void
    {
        if ($filename === null || $filename === '') {
            return;
        }

        $filename = basename($filename); // never trust a stored path

        foreach ([self::AVATAR_DIR . $filename, self::AVATAR_DIR . 'thumbs/' . $filename] as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function ensureDir(string $dir): void
    {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function findOr404(int $id): array
    {
        $user = $this->users->find($id);

        if ($user === null) {
            throw PageNotFoundException::forPageNotFound("User #{$id} was not found.");
        }

        return $user;
    }
}
