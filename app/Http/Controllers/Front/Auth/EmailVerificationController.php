<?php

namespace App\Http\Controllers\Front\Auth;

use App\Domain\Entities\User;
use App\Http\Controllers\BaseController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends BaseController
{
    /**
     * メール認証リンクを検証してリダイレクトします。
     */
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        /** @var ?User $user */
        $user = User::find($id);

        if (!$user || !hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Invalid verification link');
        }

        if (!URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect(config('app.url') . '/home?verified=1');
    }
}
