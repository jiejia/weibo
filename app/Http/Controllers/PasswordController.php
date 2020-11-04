<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PasswordController extends Controller
{
    //
    /**
     * 申请密码重置
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @version  2020-11-3 19:36
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function email(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'bail',
                'required',
                'email',
                'exists:users',
                function ($attribute, $value, $fail) use ($request) {
                    $reset = PasswordReset::where(['email' => $value])->orderByDesc('created_at')->first();
                    if (! $reset) {
                        return true;
                    }
                    // 检查 token 是否过期
                    $now = Carbon::now();
                    $expire = Carbon::parse($reset->created_at)->addSeconds(60);

                    if ($now->lt($expire)) {
                        $seconds = $now->diffInSeconds($expire);
                        return $fail('密码重置还未已过期,请稍' . $seconds . '秒后再试'); // todo 显示过期时间
                    }
                    return true;
                }
            ]
        ]);

        $reset = PasswordReset::create([
            'email' => $request->email,
        ]);

        $this->sendEmailConfirmationTo($reset);
        session()->flash('success', '重置密码邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect()->back();
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @version  2020-11-4 10:59
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email|exists:users',
            'password' => 'bail|required|confirmed|min:6',
            'token' => [
                'bail',
                'required',
                'string',
                'exists:password_resets',
                function ($attribute, $value, $fail) use ($request) {
                    $reset = PasswordReset::where(['token' => $value, 'email' => $request->email])->orderByDesc('created_at')->first();
                    if (! $reset) {
                        return $fail('密码重置或邮箱不存在'); // todo 显示过期时间
                    }
                    // 检查 token 是否过期
                    $now = Carbon::now();
                    $expire = Carbon::parse($reset->created_at)->addSeconds(600);

                    if ($now->gt($expire)) {
                        return $fail('密码重置已过期'); // todo 显示过期时间
                    }
                    return true;
                }
            ]

        ]);

        $user = User::where(['email' => $request->email])->first();

        $user->password = bcrypt($request->password);
        $user->save();

        session()->flash('success', '密码修改成功，请重新登录');
        return redirect()->route('login');
    }

    /**
     * 申请密码重置的表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @version  2020-11-3 18:23
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function emailForm()
    {
        return view('passwords.email');
    }

    /**
     * 密码重置的表单
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Validation\ValidationException
     * @version  2020-11-4 8:09
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function resetForm($token)
    {
        $valid = Validator::make(['token' => $token], [
            'token' => [
                'bail',
                'required',
                'string',
                'exists:password_resets',
                function ($attribute, $value, $fail) {
                    $reset = PasswordReset::where(['token' => $value])->orderByDesc('created_at')->first();

                    // 检查 token 是否过期
                    $now = Carbon::now();
                    $expire = Carbon::parse($reset->created_at)->addSeconds(600);

                    if ($now->gt($expire)) {
                        $seconds = $now->diffInSeconds($expire);
                        return $fail('密码重置已过期,请重新申请'); //
                    }
                    return true;
                }
            ]
        ]);

        if($valid->fails()) {
            session()->flash('warning', '密码重置已经过期,请重新申请');
            return redirect()->route('password.email_form');
        }

        return view('passwords.reset', compact('token'));
    }

    /**
     * @param $reset
     * @version  2020-11-4 10:56
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    protected function sendEmailConfirmationTo($reset)
    {
//        $view = 'emails.password_confirm';
//        $data = compact('reset');
//        $from = 'summer@example.com';
//        $name = 'Summer';
//        $to = $reset->email;
//        $subject = "重置密码邮件。";
//
//        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
//            $message->from($from, $name)->to($to)->subject($subject);
//        });
        $view = 'emails.password_confirm';
        $data = compact('reset');
        $name = 'Summer';
        $to = $reset->email;
        $subject = "重置密码邮件。";

        Mail::send($view, $data, function ($message) use ($name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}
