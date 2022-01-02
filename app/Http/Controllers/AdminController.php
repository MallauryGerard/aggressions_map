<?php

namespace App\Http\Controllers;

use App\Models\Aggression;
use App\Models\Blacklist;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show all aggressions to moderate
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Laravel\Lumen\Application|\Laravel\Lumen\Http\Redirector
     */
    public function index(Request $request)
    {
        session_start();
        // Only the admin can access this page
        if (isset($_SESSION['username']) && $_SESSION['username'] == env('ADMIN_USERNAME')) {
            $aggressions = Aggression::orderBy('id', 'desc')->get();
            // Count number of time a
            $count_ips = [];
            foreach ($aggressions as $aggression) {
                if (isset($count_ips[$aggression->ip])) {
                    $count_ips[$aggression->ip]++;
                } else {
                    $count_ips[$aggression->ip] = 1;
                }
            }
            $aggressions = $aggressions->where('is_moderate', 0);
            return view('admin.index', ['aggressions' => $aggressions, 'count_ips' => $count_ips]);
        } else {
            return redirect(route('index'));
        }
    }

    /**
     * Login the user if the credentials are correct
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function login(Request $request) {
        // Only the admin can access this page
        if ($request->admin_username == env('ADMIN_USERNAME') && $request->admin_password == env('ADMIN_PASSWORD')) {
            session_start();
            $_SESSION['username'] = $request->admin_username;
            return redirect(route('admin.index'));
        } else {
            return redirect(route('index'));
        }
    }

    /**
     * Show login form
     *
     * @return \Illuminate\View\View|\Laravel\Lumen\Application
     */
    public function showLogin() {
        return view('admin.showLogin');
    }

    /**
     * Destroy the session
     *
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function logout() {
        session_start();
        session_destroy();
        return redirect(route('index'));
    }

    /**
     * Moderate an aggression.
     * It can be refused, accepted (attached to a "type") or blocked
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function moderate(Request $request)
    {
        session_start();
        if (!$request->ajax()) {
            abort(404);
        }
        // Only the admin can access this page.
        if (isset($_SESSION['username']) && $_SESSION['username'] == env('ADMIN_USERNAME')) {
            if ($request->type == "Bloquer") {
                if (!Blacklist::where('ip', $request->author_ip)->exists()) {
                    $blacklist = new Blacklist();
                    $blacklist->ip = $request->author_ip;
                    $blacklist->save();
                }
                $is_visible = 0;
                $type = null;
            } else if ($request->type == "Refuser") {
                $is_visible = 0;
                $type = null;
            } else {
                $is_visible = 1;
                $type = $request->type;
            }
            return response()->json([
                'success' => Aggression::where('id', $request->id)->update(['type' => $type, 'is_visible' => $is_visible, 'is_moderate' => 1])
            ]);
        } else {
            return redirect(route('index'));
        }
    }
}

