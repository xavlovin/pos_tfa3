<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    protected CustomerModel $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    /** GET /customers — listing page. */
    public function index()
    {
        return view('customers/index', [
            'title'     => 'Customers',
            'active'    => 'customers',
            'customers' => $this->customers->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    /** GET /customers/new — blank "New Customer" form. */
    public function new()
    {
        return view('customers/form', [
            'title'    => 'New Customer',
            'active'   => 'customers',
            'customer' => null,
            'action'   => site_url('customers'),
        ]);
    }

    /** POST /customers — validate, then insert. */
    public function create()
    {
        if (! $this->validate($this->rules(), $this->messages())) {
            // Redisplay the form with the errors AND the user's previous input.
            return redirect()->back()->withInput();
        }

        $this->customers->insert($this->payload());

        return redirect()->to('customers')
            ->with('success', 'Customer “' . $this->request->getPost('full_name') . '” was added.');
    }

    /** GET /customers/{id}/edit — form pre-filled with the existing record. */
    public function edit(int $id)
    {
        return view('customers/form', [
            'title'    => 'Edit Customer',
            'active'   => 'customers',
            'customer' => $this->findOr404($id),
            'action'   => site_url('customers/' . $id),
        ]);
    }

    /** POST /customers/{id} — validate, then update. */
    public function update(int $id)
    {
        $this->findOr404($id);

        if (! $this->validate($this->rules($id), $this->messages())) {
            return redirect()->back()->withInput();
        }

        $this->customers->update($id, $this->payload());

        return redirect()->to('customers')
            ->with('success', 'Customer “' . $this->request->getPost('full_name') . '” was updated.');
    }

    // ------------------------------------------------------------------

    /**
     * Validation rules. On update, the email uniqueness check ignores the
     * record being edited so saving without changes still passes.
     */
    private function rules(?int $id = null): array
    {
        $unique = $id === null
            ? 'is_unique[customers.email]'
            : "is_unique[customers.email,id,{$id}]";

        return [
            'full_name' => 'required|min_length[3]|max_length[120]',
            'email'     => "required|valid_email|max_length[150]|{$unique}",
            'phone'     => 'permit_empty|min_length[7]|max_length[30]|regex_match[/^[0-9+\-\s()]+$/]',
            'address'   => 'permit_empty|max_length[255]',
        ];
    }

    private function messages(): array
    {
        return [
            'full_name' => [
                'required'   => 'Full name is required.',
                'min_length' => 'Full name must be at least 3 characters.',
                'max_length' => 'Full name cannot exceed 120 characters.',
            ],
            'email' => [
                'required'    => 'Email address is required.',
                'valid_email' => 'Please enter a valid email address (e.g. name@example.com).',
                'is_unique'   => 'This email address already belongs to another customer.',
                'max_length'  => 'Email cannot exceed 150 characters.',
            ],
            'phone' => [
                'min_length'  => 'Phone number looks too short.',
                'max_length'  => 'Phone number cannot exceed 30 characters.',
                'regex_match' => 'Phone may only contain digits, spaces, +, - and parentheses.',
            ],
            'address' => [
                'max_length' => 'Address cannot exceed 255 characters.',
            ],
        ];
    }

    /** Only whitelisted, trimmed fields go to the database. */
    private function payload(): array
    {
        $post = $this->request->getPost(['full_name', 'email', 'phone', 'address']);

        return [
            'full_name' => trim((string) $post['full_name']),
            'email'     => strtolower(trim((string) $post['email'])),
            'phone'     => trim((string) $post['phone']) ?: null,
            'address'   => trim((string) $post['address']) ?: null,
        ];
    }

    private function findOr404(int $id): array
    {
        $customer = $this->customers->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound("Customer #{$id} was not found.");
        }

        return $customer;
    }
}
