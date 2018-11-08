<?php

namespace App\Http\Controllers;

/* The sessions controller validates the user logging in. It will check that the user is trying to login with a valid netID on
Dal's users database */
use App\LDAP;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;


class SessionsController extends Controller
{

    /* Only the guests can see this view, but logged in users can still access the 'destroy' which will log them out */
    public function __construct()

    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    /* Create login page */
    public function create()
    {
        return view('session.login');
    }

    /* Validates information and checks with Dal's database to verify that it is a valid netID that the user is trying
    to log in with */
    public function store(Request $request)
    {

        /* User ID and password that user is trying to login with */
        $netID = $request->netID;
        $Userpassword = $request->password;

        /* Tries to find user in the program's database */
        $user = User::where('netID', $netID)->first();

        /* Info to connect to Dal's server */
        $hostname = 'ds.dal.ca';
        $username = '';
        $password = '';
        $basedn = "ou=People,DC=ds,DC=dal,DC=ca";

        /* Creates an instance of the LDAP class to try to connect to Dal */
        $LDAP = new LDAP();

        /* Connection attempt to Dal through LDAP */
        $connect = $LDAP->connect($hostname, $username, $password);

        try {

            /* If connection is successful */
            if ($connect) {

                $search = $LDAP->search($basedn, $attributes = array('samaccountname' => $netID));

                /* If netID is found in Dal's database */
                if ($search) {

                    $userDN = $search[0]['dn'];

                    $entries = $LDAP->authenticate($userDN, $netID, $Userpassword);

                    /* If netID/password is authenticated */
                    if ($entries) {

                        global $_USER_DATA;

                        $_USER_DATA = $entries[0];

                        $data = '';

                        foreach ($entries[0]['memberof'] as $entry) {

                            $start = stripos($entry, 'CN=');

                            if ($start !== false) {

                                $end = stripos($entry, ',', $start);

                                $data = substr($entry, $start + 3, ($end - ($start + 3)));

                            }

                        }

                        /* If user exists in program database and user has been authenticated through Dal the user
                        is then logged in */
                        if ($user && $entries) {

                            Auth::login($user);

                            return redirect('/');

                            /* Else, user is 0 (authenticated guest) */
                        } else if (!$user && $entries) {

                            $_SESSION['error'] = 'Please register account.';

                            return back()->withErrors([

                                'message' => 'Please register an account.'

                            ]);

                        }

                        /* Incorrect password */
                    } else {

                        $_SESSION['error'] = 'Incorrect password.';

                        return back()->withErrors([

                            'message' => 'Please check your credentials and try again.'

                        ]);

                    }

                    /* Incorrect netID */
                } else {

                    $_SESSION['error'] = 'Invalid netid';

                    return back()->withErrors([

                        'message' => 'Please check your credentials and try again.'

                    ]);

                }

                /* Unable to connect */
            } else {

                $_SESSION['error'] = 'Failed to connect to AD server.';

                return back()->withErrors([

                    'message' => 'Failed to connect to AD server.'

                ]);

            }

        } catch (\Exception $e) {

            return redirect('/login')
                ->withInput($request->only('netID'))
                ->withErrors([

                    'netID' => $e->getMessage()

                ]);

        } //end catch

        return redirect('/login');

    } //end Store Function
    

    /* Logs out the user */
    public function destroy()

    {
        auth()->logout();
        return redirect('/login')->withErrors([
            'message' => 'Successfully logged out!'
        ]);
    }
}
