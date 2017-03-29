<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Org;
use App\Classroom;
use App\Info;
use App\Auth;
use App\Classify;

class ApplyController extends Controller
{
    /* This homepage method. The following values is needed by view.
     * - $org 		list of dicts of all the orgs
     *  + 0
     *   + name 	org's name
     *   + id 		org's id
     * - $orgid		id of the org that currently chosen
     * - $class 	list of dicts of all the classroom current org has
     *  + 0
     *   + name 	classroom's name
     *   + id 		classroom's id
     * - $classNow	dict of classroom that currently chosen
     *  + name 		classroom's name
     *  + start 	start of classroom's available time (hour)
     *  + end 		end of classroom's available time (hour)
     *  + notice    notice (html) for the class
     * - $auth 		bool, if the visitor is logged in
     * - $user 		user's name if he is logged in
     * - $act 		list of info of apply for current classroom of 7 days
     *  + 0 		info for the first day
     *   + week 	which day in a week
     *   + time 	what date is that day
     *   + length 	how many applies today
     *   + 0		a sample of apply
     *    + id 			id of this apply
     *    + starttime	timestamp of the start time
     *    + endtime		timestamp of the end time
     *    + status 		see $history[0]['status']
     *    + name 		name of the person who applied
     *    + org 		name of the org that the person belongs to
     *    + phone 		phone number of the person
     *    + reason 		name of the activity
     *    + handleperson	handle person's name if the apply is passed
     * - $audit 	bool, if the user currently logged in has privilege to 
     *      		verify
     * - $history 	lists for apply history of this user of this classroom
     *  + 0
     *   + status   0: waiting 1: passed 2: rejected 3: unknown
     *   + date 	apply date as 'YYYY/MM/DD'
     *   + time1    apply start time as 'HH:MM'
     *   + time2    apply end time as 'HH:MM'
     *   + reason   activity name
     *   + decreason	reason of rejecting (if rejected)
     */
    public function home()
    {
        $wenzhi = request()->path() === 'Index/wenzhi';
        $timeNow = (int)request()->get('day', time());
        $orgId = $wenzhi ? 4 : (int)request()->get('org', 1);
        $classroomId = (int)request()->get('type', -1);

        // fail as fast as possible to avoid more database query and so on
        $currentOrg = Org::find($orgId);
        if (!$currentOrg) return 'Error!';
        $currentClassroom = $classroomId > 0 ? 
        $currentOrg->classrooms()->find($classroomId) :
        $currentOrg->classrooms()->first();
        if (!$currentClassroom) return 'Error!';

        $orgs = Org::all();
        $classrooms = $currentOrg->classrooms->all();

        $username = session('username');
        $netId = session('netid');
        if (!$username)
        {
            \phpCAS::client(CAS_VERSION_2_0, 'cas.xjtu.edu.cn', 443, '');
            \phpCAS::setNoCasServerValidation();
            if (\phpCAS::checkAuthentication())
            {
                // the following weird thing is copied
                $netId = \phpCAS::getUser();
                try
                {
                    $soap = new \SoapClient(
                        'http://u.xjtu.edu.cn/axis2/services/UserInfo?wsdl');
                    $in = ['limited' => 'nic_im', 'uid' => $netId];
                    $out = $soap->getSimpleInfoById($in);
                    $result = $out->return;
                }
                catch (\Exception $e)
                {
                    return 'Error!';
                }
                $username = $result->username;
                // i am NOT responsible if the code above crash
                session(['username' => $username]);
                session(['netid' => $netId]);
            }
        }
        $auth = $username ? 1 : 0;  // does not use bool to avoid problems


        $midnight = mktime(0, 0, 0, 
            date('m', $timeNow), date('d', $timeNow), date('Y', $timeNow));
        $activity = [];
        $namesOfDays = ['', '星期一', '星期二', '星期三', '星期四', '星期五', 
        '星期六', '星期日'];
        for ($day = 0; $day < 7; $day++)
        {
            $startTime = $midnight + $day * 24 * 60 * 60;
            $endTime = $startTime + 24 * 60 * 60;
            $act = $currentClassroom->infos()->where(
                'starttime', '>', $startTime)->where(
                'endtime', '<', $endTime)->where(
                'status', '>=', 0)->where('status', '<=', 1)->get()->all();
                $activity[$day] = array_merge($act, [
                    'length' => count($act),
                    'time' => date('m月d日', $startTime),
                    'week' => $namesOfDays[date('N', $startTime)],
                    ]);
            }

            $audit = $auth ? $this->hasPrivilege($netId, $orgId) : false;

            $rawHistory = $netId ? Info::where('netid', $netId)->get()->all() : [];
            $history = [];
            foreach ($rawHistory as $his)
            {
                $history[] = [
                'status' => $his['status'],
                'decreason' => $his['decreason'],
                'date' => date('Y/m/d', $his['starttime']),
                'time1' => date('H:i', $his['starttime']),
                'time2' => date('H:i', $his['endtime']),
                'reason' => $his['reason'],
                ];
            }



            $classifies=Classify::all();
            if($orgId==1||$orgId==4)
            {
                $classify=$classifies->where('id2',1);
            }elseif($orgId==5)
            {
                $classify=$classifies->where('id2',2);

            }else
            {
                $classify="";
            }



        // dd($audit);

            return view($wenzhi ? 'index.wenzhi' : 'index.index', [
                'org'       => $orgs,
                'orgid'     => $orgId,
                'class'     => $classrooms,
                'classNow'  => $currentClassroom,
                'auth'      => $auth,
                'user'      => $username,
                'act'       => $activity,
                'audit'     => $audit,
                'history'   => $history,
                'classify'   => $classify,
                ]);
        }

        public function login()
        {
            \phpCAS::client(CAS_VERSION_2_0, 'cas.xjtu.edu.cn', 443, '');
            \phpCAS::setNoCasServerValidation();
            \phpCAS::forceAuthentication('');
            return back();
        }

        public function logout()
        {
            session()->flush();
            session()->save();
            \phpCAS::client(CAS_VERSION_2_0, 'cas.xjtu.edu.cn', 443, '');
            \phpCAS::setNoCasServerValidation();
            \phpCAS::logout();
            return back();
        }

        public function apply()
        {
            $newInfo = new Info;
            $newInfo['name']    = session('username');
            $newInfo['netid']   = session('netid');
        //

            $drink=request()->input('drink','0');
            $reset=request()->input('reset');
            $desk=0;
            $cleanup=0;
            if(in_array('desk',$reset)){$desk=1;};
            if(in_array('cleanup',$reset)){$cleanup=1;};
            $reset_drink=$cleanup.";".$desk.";".$drink;

            $feedback=request()->input('feedback');
            $email=0;
            $wechat=0;
            if(in_array('email',$feedback)){$email=1;};
            if(in_array('wechat',$feedback)){$wechat=1;};
            $feedbacksql=$email.";".$wechat;

            $newInfo['theme']   = request()->input('theme','未知主题');
            $newInfo['other']   = request()->input('other','无');
            $newInfo['email']   = request()->input('email','1281630954@qq.com');
            $newInfo['reset&drink']=$reset_drink;
            $newInfo['classify'] =request()->input('classify','1');
            $newInfo['people']  =str_replace('}','',request()->input('people','10'));
            $newInfo['feedback']=$feedbacksql;
        //
            $newInfo['reason']  = request()->input('activity', '未知活动');
            $newInfo['org']     = request()->input('org', '未知活动');
            $newInfo['phone']   = request()->input('phone', '11111111111');
            $newInfo['applytime']   = time();
            $newInfo['status']  = 0;
            $newInfo['starttime']   = request()->input('timeA');
            $newInfo['endtime'] = request()->input('timeB');
            $newInfo['type']    = request()->input('type', 1);
            $newInfo->save();
            return '申请成功';
        }

        public function orgsJson()
        {
            return Org::all();
        }

        public function audit()
        {
            $info = Info::find(request()->input('id'));
            $classroom = $info->classroom;
            if (!$this->hasPrivilege(session('netid'), $classroom->org->id))
            {
                return '无此权限';
            }

            $info['status'] = (int)request()->input('status');
            $optionalReason = request()->input('decreason');
            if ($optionalReason)
            {
                $info['decreason']  = $optionalReason;
            }
            $info['handletime'] = time();
            $info['handleperson']   = session('username');
            $info->save();

            if ($info['status'] === 2)
            {
            // send a message to the person applied. 
            // These code is copied (half).
                $c = curl_init();
                curl_setopt($c, CURLOPT_URL, 'http://api.weimi.cc/2/sms/send.html');
                curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($c, CURLOPT_POST, TRUE);
                curl_setopt($c, CURLOPT_POSTFIELDS, 
                    'uid=DJrbKAeC1yUp&pas=9v2v8tnq&mob=' . $info['phone'] . 
                    '&cid=gSsOjS0Q8eOy&p1=' . $classroom['name'] . '&p2=' . 
                    $info['handleperson'] . '&p3=' . $info['decreason'] .
                    '&type=json');
                curl_exec($c);
                curl_close($c);
                return '处理成功并已经发送信息到申请人的手机';
            }
            return '处理成功';
        }

        public function updateNotice(Classroom $classroom)
        {
            if (!$classroom)
            {
                return 'Error!';
            }
            if (!$this->hasPrivilege(session('netid'), $classroom->org->id))
            {
                return '无此权限';
            }
            $classroom['notice'] = request()->input('notice');
            $classroom->save();
            return '更新成功';
        }

        public function cancelApply(Info $info)
        {
            if (!$info)
            {
                return 'Error!';
            }
            if (session('netid') !== $info['netid'])
            {
                return '无此权限';
            }
            $info['status'] = -1;
            $info->save();
            return '撤销成功';
        }

        private function hasPrivilege($netId, $orgId)
        {
            foreach (Auth::where('netid', $netId)->get()->all() as $auth)
            {
                if ($auth->auth === $orgId)
                {
                    return true;
                }
            }
            return false;
        }
    }
