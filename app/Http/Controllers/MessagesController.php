<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Agent;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Model\Messages;
use DB;
use Validator;
use WebService;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $userLoged;

    public function __construct(Request $request){
         //if(!$request->ajax()){
        //	abort(404, 'Unauthorized action.');
        //}
        if(Auth::check()){
            $this->userLoged = Auth::user()->id;
        }

    }

    public function index()
    {
        if(Auth::check()){
            $user = User::find(Auth::user()->id);
            //if(Agent::isTablet()){return view('message.message')->with('user',$user);}elseif (Agent::isMobile()){ return view('mobile.message')->with('user',$user);}else{ return view('message.message')->with('user',$user);}
			return view('message.message')->with('user',$user);
        }
        abort(404, 'Unauthorized action.');
        return redirect()->to('/');
    }
    public function changestatus(Request $request){
        if($request->ajax()){
            if($request->has('message_id') && $request->has('message_status') ){
                $update = DB::table('message_sms')
                    ->where('id','=',$request->input('message_id'))
                    ->update(array('message_status' => $request->input('message_status')));
            }
        }

    }
    public function sendMessage(Request $request){
        //dd($this->userLoged);
        if($this->userLoged){
            $messages = array( 'required' => 'Vui lòng nhập các thông tin yêu cầu.' );
            $input = $request->all();
            //dd($input);
            $validation = Validator::make($input, Messages::$rule, $messages);
            if ($validation -> passes()) {

                if($request->has('message_group')){
                    $message_group = $request->input('message_group');
                }else{
                    $message_group = date_timestamp_get(date_create());
                }

                $data = array(
                    'message_group' => $message_group,
                    'from_member' => $this->userLoged,
                    'to_member' => $request->input('to_member'),
                    'message_title' => $request->input('message_title'),
                    'message_body' => $request->input('message_body')

                );

                Messages::create($data);
                $result['msg_code'] = 'success';
                $result['msg'] = 'Gửi thành công!';
                $u = DB::table('users')
                    ->where('id','=',$request->input('to_member'))->first();
                /*$dataEmail =  array(
                    'name' => $u->name ,
                    'email' => $u->email,
                    'content' => '<p>
                Bạn đã nhận được tin nhắn mới tại CungCap.net với nội dung sau</p>
                <p><b><i>'.$data['message_body'].'<i><b></p>
                <p>Đăng nhập ngay CungCap.net để xem tin nhắn của bạn.</p>
                '
                );

                Mail::send('emails.notification',
                    $dataEmail
                    ,function($message) use ($dataEmail) {
                        $message->from('noreply@cbr.vn');
                        $message->to($dataEmail['email'], $dataEmail['name'])
                            ->subject(config('app.appname').' - Thông báo Bạn có tin nhắn mới trong CungCap.net!');
                    });
                */
                return response()->json($result);
            }else{
                $result['msg_code'] = 'error';
                $result['msg'] = 'Có lỗi xảy ra!';
                return response()->json($result);
            }

        }
    }

    public function search(Request $request){
        if($request->ajax()){
            if($request->has('keywords')){
                $message = DB::table('message_sms')
                    ->join('users','users.id','=','message_sms.from_member')
                    ->where('message_title','like','%'.$request->input('keywords').'%');
                return view('message.message-inbox',compact('message'))->render();
            }
        }
    }

    public function messageByStatus(Request $request){
        DB::enableQueryLog();
        if($request->ajax()){
            if($request->has('message_status')){
                $status = $request->input('message_status');
                if($status == 'inbox'){
                    if($request->has('filter')){
                        $filter = $request->input('filter');
                        $message = DB::table('message_sms')
                            ->join('users','users.id','=','message_sms.from_member')
                            //->where('message_sms.from_member','!=',Auth::user()->id)
                            ->where('message_sms.to_member','=',Auth::user()->id)
                            ->where('message_sms.to_del','!=','1')
                            ->where('message_sms.message_status','=',$filter)
                            ->orderBy('message_sms.created_at','DESC')
                            ->orderBy('message_sms.message_status','DESC')
                            ->select('message_sms.*','users.name')
                            ->paginate(10);
                        return view('message.message-inbox',compact('message','filter'))->render();
                    }else{
                        $message = DB::table('message_sms')
                            ->join('users','users.id','=','message_sms.from_member')
                            //->where('message_sms.from_member','!=',Auth::user()->id)
                            ->where('message_sms.to_member','=',Auth::user()->id)
                            ->where('message_sms.to_del','!=','1')
                            ->orderBy('message_sms.created_at','DESC')
                            ->orderBy('message_sms.message_status','DESC')
                            ->select('message_sms.*','users.name')
                            ->paginate(10);
                        return view('message.message-inbox',compact('message'))->render();
                    }


                }

                if($status == 'outbox'){
                    $message = DB::table('message_sms')
                        ->join('users','users.id','=','message_sms.to_member')
                        ->where('message_sms.from_member','=',Auth::user()->id)
                        ->where('message_sms.from_del','!=','1')
                        ->orderBy('message_sms.created_at','DESC')
                        ->select('message_sms.*','users.name')
                        ->paginate(10);
                    return view('message.message-out',compact('message'))->render();

                }

                if($status == 'destroy'){
                    $message = DB::table('message_sms')
                        ->orWhere(function($query)
                        {
                            $query->where('message_sms.from_member', '=', Auth::user()->id)
                                ->where('message_sms.from_del', '=', '1');
                        })
                        ->orWhere(function($query)
                        {
                            $query->where('message_sms.to_member', '=', Auth::user()->id)
                                ->where('message_sms.to_del', '=', '1');
                        })
                        ->paginate(1);
                    return view('message.message-destroy',compact('message'))->render();
                }

            }
        }
    }

    public function messageUnread(){
        if(Auth::check()){
            $message = DB::table('message_sms')
                ->where('to_member','=',Auth::user()->id)
                ->where('from_member','!=',Auth::user()->id)
                ->where('message_status','=','unread')
                ->select(DB::raw('count(*) as message_unread'))->get();
            return response()->json($message);
        }
    }
    public function messageDetail(Request $request){
        //DB::enableQueryLog();
        $message_group = $request->input('mesage_group');


        $message = DB::table('message_sms')
            ->join('users','users.id','=','message_sms.from_member')
            ->where('message_sms.message_group','=',$message_group)
            ->select('message_sms.*','avata','name')
            ->orderBy('created_at','ASC')
            ->paginate(20);
        //$query = DB::getQueryLog();
        //dd($query);
        //dd($message);
        return view('message.message-detail',compact('message'))->render();
    }

    public function messageByID(Request $request){
        if($request->has('message_id')){
            $message_id = $request->input('message_id');

            $message = DB::table('message_sms')
                ->where('message_sms.id','=',$message_id)->update(array('message_status' => 'read'));
            return view('message.message-destroy-detail',compact('message'))->render();
        }

    }
    public function messageDestroy(Request $request)
    {
        //
        //DB::enableQueryLog();
        if($request->ajax()){
            if($request->has('message_id')){
                $message_id =  explode(',',$request->input('message_id'));
                $del_state = $request->input('del_state');
                if($del_state == 'to_del'){
                    $update_data = (array(
                        //'message_status' => 'destroy',
                        'to_del' => '1',
                    ));
                }

                if($del_state == 'from_del'){
                    $update_data = (array(
                        //'message_status' => 'destroy',
                        'from_del' => '1',
                    ));
                }

                $update = DB::table('message_sms')
                    ->whereIn('id',$message_id)
                    ->update($update_data );

                if($update){
                    return json_encode(array('r','success'));
                }
                //$query = DB::getQueryLog();
                //dd($query);
            }
        }



    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
