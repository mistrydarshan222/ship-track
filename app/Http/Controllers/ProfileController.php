<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ChangeLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $originalData = $user->toArray();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Log the update
        $this->logChange('User', $user->id, 'updated', [
            'before' => $originalData,
            'after' => $user->toArray()
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $originalData = $user->toArray();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log the deletion
        $this->logChange('User', $user->id, 'deleted', $originalData);

        return Redirect::to('/');
    }

    /**
     * Log changes made to the user profile.
     */
    protected function logChange($model, $modelId, $action, $changes)
    {
        ChangeLog::create([
            'user_id' => Auth::id(),
            'model' => $model,
            'model_id' => $modelId,
            'action' => $action,
            'changes' => json_encode($changes),
        ]);
    }
}