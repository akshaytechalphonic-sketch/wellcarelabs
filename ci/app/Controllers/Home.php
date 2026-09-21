<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $helpers = ["form", "url"];

    public function __construct()
    {
        $session = \Config\Services::session();
        $db = db_connect();

        // Ensure CodeIgniter always uses IST
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index(): string
    {
        return view('welcome_message');
    }

    /**
     * Gateway callback (success or failed)
     */
    public function paymentSuccess()
    {
        $db = db_connect('default');

        // Read status from gateway
        $statusRaw = $_REQUEST['status'] ?? '';
        $status    = strtolower($statusRaw);

        // Define success values
        $isSuccess = in_array($status, ['success', 'captured', 'completed']);

        $postdata = [
            'user_id'      => $_REQUEST['udf1'] ?? null,
            'txnid'        => $_REQUEST['txnid'] ?? '',
            'status'       => $isSuccess ? 'Success' : 'Failed',
            'amount'       => $_REQUEST['net_amount_debit'] ?? 0,
            'name'         => $_REQUEST['firstname'] ?? '',
            'phone'        => $_REQUEST['phone'] ?? '',
            'payment_type' => $_REQUEST['productinfo'] ?? '',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];


        $builder = $db->table('payments');
        $builder->insert($postdata);
        $last_id = $db->insertID();

        // Redirect based on status
        if ($isSuccess) {
            $redirectUrl = 'https://wellcarelabs.in/paymentSuccess?ID=' . base64_encode($last_id);
        } else {
            $redirectUrl = 'https://wellcarelabs.in/paymentFailed?ID=' . base64_encode($last_id);
        }

        return redirect()->to($redirectUrl);
    }

    /**
     * Separate failed (optional)
     */
    public function paymentFailed()
    {
        $db = db_connect('default');

        $postdata = [
            'user_id'      => $_REQUEST['udf1'] ?? '',
            'txnid'        => $_REQUEST['txnid'] ?? '',
            'status'       => 'Failed',
            'amount'       => $_REQUEST['net_amount_debit'] ?? 0,
            'name'         => $_REQUEST['firstname'] ?? '',
            'phone'        => $_REQUEST['phone'] ?? '',
            'payment_type' => $_REQUEST['productinfo'] ?? '',
            'addedon'      => date('Y-m-d H:i:s'),
        ];

        $builder = $db->table('payments');
        $builder->insert($postdata);
        $last_id = $db->insertID();

        return redirect()->to('https://wellcarelabs.in/paymentFailed?ID=' . base64_encode($last_id));
    }
}
