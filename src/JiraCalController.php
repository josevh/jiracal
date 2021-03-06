<?php

namespace Josevh\JiraCal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class JiraCalController extends Controller
{

    public function index()
    {
        if (!session('jira-auth')) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@login')->withErrors(['message' => 'Login required']);
        }

        try {
            $api = JiraCalHelper::jiraApi();
            $projects = $api->getProjects();
        } catch (\chobie\Jira\Api\UnauthorizedException $ue) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Jira authentication failed, please try again.'
            ]);
        } catch (\chobie\Jira\Api\Exception $ex1) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Error: ' . $ex1
            ]);
        } catch (\Exception $ex2) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Error: ' . $ex2
            ]);
        }
        $projectList = [];
        foreach ($projects->getResult() as $key => $project) {
            if (isset($project['key']) && isset($project['name'])) {
                $projectList[$project['key']] = $project['name'];
            }
        }
        return view('jiracal::index', compact('projectList'));
    }

    /**
     * Login form for Jira.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (session()->get('_previous')['url'] == url()->current()
			|| !str_contains(session()->get('_previous')['url'], config('jiracal.path'))) {
            $nextURL = url(config('jiracal.path'));
        } else {
            $nextURL = session()->get('_previous')['url'];
        }
        session()->flash('_loginref', $nextURL);
        return view('jiracal::login');
    }

    /**
     * Logout form for Jira.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if ($request->session()->has('jira-auth')) {
	    $request->session()->forget('jira-auth');
	    $request->session()->forget('jira-username');
	    $request->session()->forget('jira-password');
            return redirect()->action('\Josevh\JiraCal\JiraCalController@index');
        } else {
            return redirect()->back()->withErrors([
                'message' => 'You are not signed in.'
            ]);
        }
    }

    /**
     * Check jira credentials and validate.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request)
    {
        // should never happen, is checked in view
        if ( is_null(config('jiracal.jira_url')) ) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Jira URL is not configured.'
            ]);
        }

        $username = '';
        $password = '';
        if ($request->has('guest') && $request->input('guest') == 'true') {
            if (!is_null(config('jiracal.guest_jira_username')) && !is_null(config('jiracal.guest_jira_password'))) {
                $username = config('jiracal.guest_jira_username');
                $password = config('jiracal.guest_jira_password');
            } else {
                return redirect()->back()->withInput()->withErrors([
                    'message' => 'Jira guest is not configured.'
                ]);
            }
        } else {
            $this->validate($request, [
                'jira-username' => 'required',
                'jira-password' => 'required',
            ]);
            $username = $request->input('jira-username');
            $password = $request->input('jira-password');
        }

        try {
	    $api = JiraCalHelper::jiraApi($username, $password);
            $projects = []; //TODO: better initialization?
            $projects = $api->getProjects();
        } catch (\chobie\Jira\Api\UnauthorizedException $ue) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Jira authentication failed, please try again.'
            ]);
        } catch (\chobie\Jira\Api\Exception $ex1) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Error: ' . $ex1
            ]);
        } catch (\Exception $ex2) {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Error: ' . $ex2
            ]);
        }
        if ($projects != false) {
            session([
                'jira-username' => $username,
                'jira-password' => \Crypt::encrypt($password),
                'jira-auth'     => true
            ]);

            // return redirect()->action('\Josevh\JiraCal\JiraCalController@index');
            return redirect()->to(session()->get('_loginref'));
        } else {
            return redirect()->back()->withInput()->withErrors([
                'message' => 'Unable to connect to Jira, please try again.'
            ]);
        }
    }

    /**
     * Redirect to listing of months for current year
     * @param  String  $key     The project key
     * @return \Illuminate\Http\Response
     */
    public function pkey($key){
        return redirect()->action('\Josevh\JiraCal\JiraCalController@year', [$key, (new \DateTime())->format('Y')]);
    }

    /**
     * Display listing of months
     * @param  String  $key     The project key
     * @param  Integer $year    The year to display
     * @return \Illuminate\Http\Response
     */
    public function year($key, $year) {
        if (!session('jira-auth')) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@login')->withErrors(['message' => 'Login required']);
        }

        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
        return view('jiracal::_year', compact('year', 'months', 'key'));
    }

    /**
     * Display a calendar for the month
     * @param  String  $key     The project key
     * @param  Integer $year    The year to display
     * @param  Integer $month   The month to display
     * @return \Illuminate\Http\Response
     */
    public function month($key, $year, $month) {
        if (!session('jira-auth')) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@login')->withErrors(['message' => 'Login required']);
        }

        $key = strtoupper($key);
        if ($month > 12 || $month == 0) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@year', [$year]);
        }
        // jira issues
        $issues             = JiraCalHelper::jiraIssues($key, $year, $month, 1, 'month');
        $createIssueLink    = JiraCalHelper::jiraCreateIssueLink($key);
        // map render prep
        $today              = new Carbon();
        $cDate              = Carbon::createFromDate($year, $month, 01);
        $daysOfWeek         = array('S','M','T','W','Th','F','Sa');
        $firstDayOfMonth    = mktime(0,0,0,$month,1,$year);
        $dateComponents     = getdate($firstDayOfMonth);
        $maxDays            = date('t',$firstDayOfMonth);
        return view('jiracal::_month', compact(
            'issues',
            'today',
            'cDate',
            'daysOfWeek',
            'dateComponents',
            'maxDays',
            'key',
            'createIssueLink'
        ));
    }

    /**
     * Display a day of the day
     * @param  String  $key     The project key
     * @param  Integer $year    The year to display
     * @param  Integer $month   The month to display
     * @param  Integer $day     The day to display
     * @return \Illuminate\Http\Response
     */
    public function day($key, $year, $month, $day) {
        if (!session('jira-auth')) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@login')->withErrors(['message' => 'Login required']);
        }

        $key = strtoupper($key);
        $maxDays = date('t',mktime(0,0,0,$month,1,$year));
        if ($day > $maxDays || $day == 0) {
            return redirect()->action('\Josevh\JiraCal\JiraCalController@month', [$year, $month]);
        }
        $cDate = Carbon::createFromDate($year, $month, $day);
        $nDate = $cDate->copy()->addDay();
        $pDate = $cDate->copy()->subDay();
        $issues = JiraCalHelper::jiraIssues($key, $year, $month, $day, 'day');
        $createIssueLink    = JiraCalHelper::jiraCreateIssueLink($key);
        return view('jiracal::_day', compact('year', 'month', 'day', 'issues', 'cDate', 'nDate', 'pDate', 'key', 'createIssueLink'));
    }

}
