<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        $user = User::find((int)$body['id']);
        $user->password = $body['password'];
        $user->email = $body['email'];
    
        $user->save();
    }

    public function addDiscord(User $user)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, env('DISCORD_API_TOKEN_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'code' => $_GET['code'],
            'client_id' => env('DISCORD_ID'),
            'client_secret' => env('DISCORD_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost:8000/api/users/add_discord'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        $result = curl_exec($ch);
    
        if (!$result)
        {
            echo curl_error($ch);
        }
    
        $result = json_decode($result, true);
    
        $access_token = $result['access_token'];
    
        $users_url = env('DISCORD_API_USER_URL');
        $header = array("Authorization: Bearer $access_token", "Content-Type: application/x-xxx-form-urlencoded");
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $users_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    
        $result = curl_exec($ch);
    
        $result = json_decode($result, true);
    
        $avatar = $result["avatar"];
    
        $user = new User();
        $user->discord_id = $result['id'];
        $user->username = $result['username'];
        $user->avatar = "https://cdn.discordapp.com/avatars/$user->discord_id/$avatar.png";
    
        $user->save();
    
        return redirect(env('APP_FRONTEND') . "/login?id=$user->id");
    }

    public function get(string $id)
    {
        return User::find($id);
    }

    public function getAll()
    {
        $users = User::paginate(10);

        return response()->json($users);
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        //
    }
}
