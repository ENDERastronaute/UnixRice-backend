<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/{id}', function (string $id) {
    $user = User::where('id', $id)->take(1)->get();

    echo response()->json($user);
});

Route::get('/users', function () {
    $users = User::paginate(10);

    echo response()->json($users);
});

Route::post('/user', function (Request $request) {
    $user = User::find($request->id);
    $user->password = $request->password;
    $user->email = $request->email;

    $user->save();
});

Route::get('/users/add_discord', function (Request $request) {
    $token_url = 'https://discord.com/api/oauth2/token';

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
    $user->avatar = "https://cdn.discordapp.com/avatars/$user->discord_id/$avatar.png";

    $user->save();

    return redirect(env('APP_FRONTEND') . "/login?id=$user->id");
});