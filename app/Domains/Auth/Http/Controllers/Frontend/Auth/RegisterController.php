<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Services\UserService;
use App\Rules\Captcha;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Intervention\Image\Facades\Image;

/**
 * Class RegisterController.
 */
class RegisterController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * RegisterController constructor.
     *
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Where to redirect users after registration.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(homeRoute());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        abort_unless(config('boilerplate.access.user.registration'), 404);

        return view('frontend.auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        //dd($data);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => array_merge(['max:100'], PasswordRules::register($data['email'] ?? null)),
            'profile_picture' => ['nullable', 'image', 'max:2048'], 
            'terms' => ['required', 'in:1'],
            'g-recaptcha-response' => ['required_if:captcha_status,true', new Captcha],
        ], [
            'terms.required' => __('You must accept the Terms & Conditions.'),
            'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Domains\Auth\Models\User|mixed
     *
     * @throws \App\Domains\Auth\Exceptions\RegisterException
     */
    // protected function create(array $data)
    // {
    //     abort_unless(config('boilerplate.access.user.registration'), 404);

    //     return $this->userService->registerUser($data);
    // }
    protected function create(array $data)
{
    abort_unless(config('boilerplate.access.user.registration'), 404);

   
    $user = $this->userService->registerUser($data);

    // if (isset($data['profile_picture'])) {
    //     $profilePicturePath = $data['profile_picture']->store('profile_pictures', 'public');
    //     $user->profile_picture = $profilePicturePath;
    //     $user->save();
    // }
    // if (isset($data['profile_picture'])) {
    // $baseDir = 'profile_pictures';
    // $name = sha1(time().$data['profile_picture']->hashName());
    // $extension = $data['profile_picture']->extension();
    // $fileName = "{$name}.{$extension}";

    // $data['profile_picture']->move(public_path().'/'.$baseDir, $fileName);
    // $user->profile_picture = "{$baseDir}/{$fileName}";
    // $user->save();
    // }
    // use Intervention\Image\Facades\Image;

if (isset($data['profile_picture'])) {
    $baseDir = 'profile_pictures';
    $name = sha1(time().$data['profile_picture']->hashName());
    $extension = $data['profile_picture']->extension();
    $fileName = "{$name}.{$extension}";

    // Move the uploaded image to the public directory
    $data['profile_picture']->move(public_path().'/'.$baseDir, $fileName);

    // Get the full path to the uploaded image
    $imagePath = public_path($baseDir.'/'.$fileName);

    // Open the image using Intervention Image
    $image = Image::make($imagePath);

    // Resize the image (adjust the width and height as needed)
    $image->resize(150, 150); // For example, resize to 200x200 pixels

    // Save the resized image back to the same path
    $image->save($imagePath);

    // Update the user's profile_picture column with the new path
    $user->profile_picture = "{$baseDir}/{$fileName}";
    $user->save();
}


    return $user;
}

}
