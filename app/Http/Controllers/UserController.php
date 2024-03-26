<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function unsignedDocuments(User $user)
    {
    
        $userId = $user->id;

        $user = User::findOrFail($userId);

        $unsignedInvoices = $user->unsignedInvoices()->get();

        return response()->json([
            'data' => $unsignedInvoices
        ]);
    }
}
