<?php
\App\Models\User::where('role', 'owner')->update(['password' => \Illuminate\Support\Facades\Hash::make('password')]);
echo "Password reset to 'password'\n";
